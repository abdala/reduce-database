<?php

namespace Reduce\Db;

use Doctrine\DBAL\Connection as DBAL;
use Reduce\Db\Query\QueryBuilder;

class Connection extends DBAL
{    
    protected $single = false;
    
    public function __call($name, $args)
    {
        $resultSet = $this->createResultSet()->select('*')->from($name);
        $resultSet->setTableName($name);
        
        if (count($args)) {
            call_user_func_array([$resultSet, 'where'], $args);
        }
        
        $resultSet->setSingle($this->single);
        $this->single = false;
        
        return $resultSet;
    }
    
    public function __get($name)
    {
        $this->single = true;
        return $this->$name();
    }
    
    public function createResultSet()
    {
        return new ResultSet(new QueryBuilder($this));
    }
}
