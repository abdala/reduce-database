<?php

namespace Reduce\Db;
    
use Iterator;
use ArrayAccess;
use Countable;
use JsonSerializable;
use Reduce\Db\Row;
use Reduce\Db\Query\QueryBuilder;
    
class ResultSet implements Iterator, ArrayAccess, Countable, JsonSerializable 
{
    protected $queryBuilder;
    protected $data;
    protected $single = false;
    protected $executed = false;
    protected $tableName;
    
    public function __construct($tableName, QueryBuilder $queryBuilder, $singleResultSet = false) 
    {
        $this->tableName    = $tableName;
        $this->queryBuilder = $queryBuilder;
        $this->single       = $singleResultSet;
        
        $this->queryBuilder->select('*')->from($tableName);
    }
    
    public function __call($name, $args)
    {
        if (method_exists($this->getQueryBuilder(), $name)) {
            call_user_func_array([$this->getQueryBuilder(), $name], $args);
            return $this;
        }
        
        $condition = sprintf('%s.id = %s.%s_id', $this->getTableName(), $name, $this->getTableName());
        
        $this->queryBuilder->leftJoin($this->getTableName(), $name, $name, $condition);
        
        return $this;
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }
    
    protected function execute()
    {
        if (!$this->executed) {
            $this->data = $this->getQueryBuilder()->execute()->fetchAll();
            $this->executed = true;
        }
    }
    
    protected function createRow($data = [])
    {
        return new Row($this, $data);
    }
    
    public function offsetGet($key)
    {
        if ($this->single) {
            $this->getQueryBuilder()->where('id = ?', $key);
            
            return $this->createRow();
        }
        
        if (isset($this->data[$key])) {
            $this->execute();
            return $this->data[$key];
        }
        
        return [];
    }
    
    public function offsetSet($key, $value)
    {}
    
    public function offsetUnset($key)
    {}
    
    public function offsetExists($key)
    {
		return !! $this->offsetGet($key);
    }
    
    public function count()
    {
        $this->execute();
        return count($this->data);
    }
    
	public function rewind() 
    {
        $this->execute();
        reset($this->data);
	}
	
	public function current() 
    {
        return $this->createRow(current($this->data));
	}
	
	public function key() 
    {
        $this->execute();
		return key($this->data);
	}
	
	public function next() 
    {
		next($this->data);
	}
	
	public function valid() 
    {
        $key = key($this->data);
        return ($key !== null);
    }
    
    public function toArray()
    {
        $this->execute();
        return $this->data;
    }
    
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    public function __toString()
    {
        return $this->getQueryBuilder()->__toString();
    }
}
