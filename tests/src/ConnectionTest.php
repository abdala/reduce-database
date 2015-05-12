<?php

namespace Reduce\Test\Db;

use Reduce\Db\Connection;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Reduce\Db\Query\QueryBuilder;
    
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    const TABLE_NAME = 'tableName';
    
    protected $connection;
    protected $queryBuilder;
    protected $resultSet;
    
    protected function setUp()
    {
        $this->connection = $this->getMockBuilder('Reduce\Db\Connection')
                                 ->setConstructorArgs([['global' => ['memory' => true]], new Driver])
                                 ->setMethods(['createResultSet' , 'createQueryBuilder'])
                                 ->getMock();
        
        $this->queryBuilder = $this->getMockBuilder('Reduce\Db\Query\QueryBuilder')
                                   ->setConstructorArgs([$this->connection])
                                   ->getMock();

        $this->queryBuilder->expects($this->once())
                           ->method('select')
                           ->will($this->returnSelf())
                           ->with($this->stringContains('*'));
        
        $this->queryBuilder->expects($this->once())
                           ->method('from')
                           ->will($this->returnSelf())
                           ->with($this->stringContains(self::TABLE_NAME));
        
        $this->resultSet = $this->getMockBuilder('Reduce\Db\ResultSet')
                                ->setConstructorArgs([self::TABLE_NAME, $this->queryBuilder])
                                ->getMock();
        
        $this->connection->method('createQueryBuilder')
                         ->will($this->returnValue($this->queryBuilder));
        
        $this->connection->method('createResultSet')
                         ->will($this->returnValue($this->resultSet));
    }
    
    public function testCreateResultSetFromMagicCall()
    {
        $this->resultSet->expects($this->once())
                        ->method('setSingle')
                        ->with($this->isFalse());
        
        $resultSet = $this->connection->tableName();
        
        $this->assertInstanceOf('Reduce\Db\ResultSet', $resultSet);
    }
    
    public function testCreateResultSetFromMagicGet()
    {
        $row = $this->getMockBuilder('Reduce\Db\Row')
                    ->setConstructorArgs([$this->resultSet])
                    ->getMock();
        
        $this->resultSet->expects($this->once())
                        ->method('setSingle')
                        ->with($this->isTrue());
        
        $this->resultSet->expects($this->once())
                        ->method('offsetGet')
                        ->with(1)
                        ->will($this->returnValue($row));
        
        $row = $this->connection->tableName[1];
        
        $this->assertInstanceOf('Reduce\Db\Row', $row);
    }
}