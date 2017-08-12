<?php

namespace Reduce\Db;

use Iterator;
use ArrayAccess;
use Countable;
use JsonSerializable;
use Reduce\Db\ResultSet;

class Row implements Iterator, ArrayAccess, Countable, JsonSerializable
{
    protected $data;
    protected $resultSet;
    
    public function __construct(ResultSet $resultSet, $data = [])
    {
        $this->resultSet = $resultSet;
        $this->data      = $data;
    }
    
    public function __get($name)
    {
        if(isset($this[$name . '_id'])) {
            $field = $this[$name . '_id'];
            
            return $this->getConnection()->{$name}[$field];
        }
        
        trigger_error('Undefined field: ' . $name . '_id on table ' . $this->getTableName());
    }
    
    public function __call($name, $args)
    {
        $resultSet = call_user_func_array([$this->getConnection(), $name], $args);
        $condition = sprintf("%s.%s_id = ?", $name, $this->getTableName());
        
        $resultSet->where($condition, $this['id']);
        
        return $resultSet;
    }
    
    public function getConnection()
    {
        return $this->resultSet->getQueryBuilder()->getConnection();
    }
    
    public function getTableName()
    {
        return $this->resultSet->getTableName();
    }
    
    protected function execute()
    {
        $data = $this->resultSet->toArray();
        
        if (count($data)) {
            $this->data = $data[0];
        }
    }
    
    public function offsetGet($key)
    {
        if (! count($this->data)) {
            $this->execute();
        }
        
        return $this->data[$key];
    }
    
    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }
    
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
        return current($this->data);
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
    
    public function jsonSerialize()
    {
	$this->execute();
        return $this->data;
    }
    
    public function __toString()
    {
        return $this->resultSet->getQueryBuilder()->__toString();
    }
}
