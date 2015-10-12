<?php

namespace Reduce\Db;

use Doctrine\DBAL\Connection as DBAL;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

class Connection extends DBAL
{    
    protected $single = false;
    
    public function __construct(array $params, Driver $driver, Configuration $config = null,
            EventManager $eventManager = null)
    {
        parent::__construct($params, $driver, $config, $eventManager);
    }
    
    public function __call($name, $args)
    {
        $resultSet = $this->createResultSet($name);
        
        if (count($args)) {
            call_user_func_array([$resultSet, 'where'], $args);
        }
        
        $this->single = false;
        
        return $resultSet;
    }
    
    public function __get($name)
    {
        $this->single = true;
        
        return $this->$name();
    }
    
    public function createQueryBuilder()
    {
        return new Query\QueryBuilder($this);
    }
    
    protected function createResultSet($tableName)
    {
        return new ResultSet($tableName, $this->createQueryBuilder(), $this->single);
    }
}
