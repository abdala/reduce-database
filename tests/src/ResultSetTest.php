<?php

namespace Reduce\Test\Db;

use Reduce\Db\ResultSet;
use Reduce\Db\Connection;
use Reduce\Db\Query\QueryBuilder;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;

class ResultSetTest extends \PHPUnit_Framework_TestCase
{
    const TABLE_NAME = 'tableName';
    protected $resultSet;
        
    protected function setUp()
    {
        $connection = $this->getMockBuilder('Reduce\Db\Connection')
                           ->setConstructorArgs(['global' => ['memory' => true], new Driver])
                           ->getMock();
        
        $this->resultSet = new ResultSet(static::TABLE_NAME, new QueryBuilder($connection));
    }
        
    /** @test */
    public function getTableName()
    {
        $this->assertEquals('tableName', $this->resultSet->getTableName());
    }
    
    /** @test */
    public function simpleSelect()
    {
        $sql = 'SELECT * FROM ' . static::TABLE_NAME;
        $this->assertEquals((string) $this->resultSet, $sql);
    }
    
    /** @test */
    public function addingOrderBy()
    {
        $this->resultSet->orderBy('id');
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' ORDER BY id ASC';
        
        $this->assertEquals((string) $this->resultSet, $sql);
    }
    
    /** @test */
    public function addingWhere()
    {
        $this->resultSet->where('id', 1);
        
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = ?';
        
        $this->assertEquals((string) $this->resultSet, $sql);
    }
    
    /** @test */
    public function addingWhereInArray()
    {
        $this->resultSet->where(['id' => 1]);
        
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = ?';
        
        $this->assertEquals((string) $this->resultSet, $sql);
    }
    
    /** @test */
    public function addingFullSyntax()
    {
        $this->resultSet->where('id = ?', 1);
        
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = ?';
        
        $this->assertEquals((string) $this->resultSet, $sql);
    }

    /** @test */
    public function addingWhereWithoutValue()
    {
        $this->resultSet->where('id IS NULL');
        
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id IS NULL';
        
        $this->assertEquals((string) $this->resultSet, $sql);
    }
}