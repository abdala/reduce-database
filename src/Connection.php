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
        if (! isset($params['resultSetClass'])) {
            $params['resultSetClass'] = 'Reduce\Db\ResultSet';
        }
        
        if (! isset($params['queryBuilderClass'])) {
            $params['queryBuilderClass'] = 'Reduce\Db\Query\QueryBuilder';
        }
        
        parent::__construct($params, $driver, $config, $eventManager);
    }
    
    public function __call($name, $args)
    {
        $resultSet = $this->createResultSet($name);
        
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
    
    public function createQueryBuilder()
    {
        $queryBuilderClass = $this->getParams()['queryBuilderClass'];
        return new $queryBuilderClass($this);
    }
    
    protected function createResultSet($tableName)
    {
        $resultSetClass = $this->getParams()['resultSetClass'];
        return new $resultSetClass($tableName, $this->createQueryBuilder());
    }
}
