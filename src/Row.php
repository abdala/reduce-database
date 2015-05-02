<?php

namespace Reduce\Db;

use ArrayObject;
use JsonSerializable;
use Reduce\Db\Connection;

class Row extends ArrayObject implements JsonSerializable
{
    protected $tableName;
    protected $connection;
    
    public function __get($name)
    {
        if(isset($this[$name . '_id'])) {
            $id = $this[$name . '_id'];
            
            return $this->getConnection()->{$name}[$id];
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
        return $this->connection;
    }
    
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }
    
    public function jsonSerialize()
    {
        return (array) $this;
    }
}
