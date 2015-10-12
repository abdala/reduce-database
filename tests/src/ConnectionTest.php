<?php

namespace Reduce\Test\Db;

use Reduce\Db\Connection;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Reduce\Db\Query\QueryBuilder;
    
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    const TABLE_NAME = 'tableName';
    
    protected $connection;
    
    protected function setUp()
    {
        $this->connection = new Connection(['global' => ['memory' => true]], new Driver);
    }
    
    /** @test */
    public function createQueryBuilder()
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        
        $this->assertInstanceOf('Reduce\Db\Query\QueryBuilder', $queryBuilder);
    }
    
    /** @test */
    public function createResultSetFromMagicCall()
    {
        $resultSet = $this->connection->{self::TABLE_NAME}();
        
        $this->assertInstanceOf('Reduce\Db\ResultSet', $resultSet);
    }
    
    /** @test */
    public function createResultSetFromMagicGet()
    {
        $row = $this->connection->{self::TABLE_NAME}[1];
        
        $this->assertInstanceOf('Reduce\Db\Row', $row);
    }
    
    /** @test */
    public function addingWhereOnFirstCall()
    {
        $resultSet = $this->connection->{self::TABLE_NAME}('name', 'value');
        
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE name = ?';
        
        $this->assertEquals((string) $resultSet, $sql);
    }
}