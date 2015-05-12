<?php

namespace Reduce\Test\Db;

use Reduce\Db\ResultSet;
use Reduce\Db\Connection;
use Reduce\Db\Query\QueryBuilder;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;

class ResultSetTest extends \PHPUnit_Framework_TestCase
{
    protected $instance;
        
    protected function setUp()
    {
        $connection = $this->getMockBuilder('Reduce\Db\Connection')
                           ->setConstructorArgs(['global' => ['memory' => true], new Driver])
                           ->getMock();
        
        $queryBuilder = $this->getMockBuilder('Reduce\Db\Query\QueryBuilder')
                             ->setConstructorArgs([$connection])
                             ->getMock();
        
        $queryBuilder->method('select')
                     ->will($this->returnSelf());
        
        $this->instance = new ResultSet('tableName', $queryBuilder);
    }
    
    public function testExecuteQueryBuilderFromMagicCall()
    {
        $this->instance->getQueryBuilder()
                       ->expects($this->once())
                       ->method('orderBy')
                       ->with($this->stringContains('id'));
        
        $this->instance->orderBy('id');
    }
    
    public function testOffsetGetCall()
    {
        // offsetGet
    }
}