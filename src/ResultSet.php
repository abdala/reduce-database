<?php

namespace Reduce\Db;
    
use Iterator;
use ArrayAccess;
use Countable;
use JsonSerializable;
use Reduce\Db\Row;
    
class ResultSet implements Iterator, ArrayAccess, Countable, JsonSerializable 
{
    protected $queryBuilder;
    protected $data;
    protected $single = false;
    protected $executed = false;
    protected $tableName;
    
    public function __construct($queryBuilder) 
    {
        $this->queryBuilder = $queryBuilder;
    }
    
    public function __call($name, $args)
    {
        if (method_exists($this->getQueryBuilder(), $name)) {
            call_user_func_array([$this->getQueryBuilder(), $name], $args);
            return $this;
        }
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
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
    
    public function setSingle($single)
    {
        $this->single = $single;
    }
    
    public function offsetGet($key)
    {
        if ($this->single) {
            $this->getQueryBuilder()->where('id = ?', $key);
            $this->execute();
            
            if (count($this->data)) {
                return $this->createRow($this->data[0]);
            }
        }
        
        if (isset($this->data[$key])) {
            $this->execute();
            return $this->data[$key];
        }
        
        return [];
    }
    
    public function createRow($data)
    {
        $row = new Row($data);
        
        $row->setTableName($this->getTableName());
        $row->setConnection($this->getQueryBuilder()->getConnection());
        
        return $row;
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