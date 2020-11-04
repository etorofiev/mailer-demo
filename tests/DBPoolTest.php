<?php

namespace Mailer;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class DBPoolTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        $dotenv->required(['MYSQL_MAX_CONNECTIONS', 'MYSQL_DB_HOST', 'MYSQL_DB_PORT', 'MYSQL_DB_NAME', 'MYSQL_DB_USER', 'MYSQL_DB_PASSWORD']);
    }

    public function testAcquireConnection()
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();

        $this->assertInstanceOf(DB::class, $connection);
        $pool->releaseConnection($connection);
    }

    public function testConnectionsNumber()
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $connection2 = $pool->getConnection();
        $connection3 = $pool->getConnection();

        $this->assertEquals(3, $pool->getBusy());
        $pool->releaseConnection($connection);
        $pool->releaseConnection($connection2);
        $pool->releaseConnection($connection3);
    }

    public function testTooManyConnections()
    {
        $maxConnections = $_ENV['MYSQL_MAX_CONNECTIONS'];

        $pool = DBPool::getInstance();
        $connections = [];

        $this->expectException(\RuntimeException::class);

        for ($i = 0; $i < $maxConnections + 1; $i++) {
            $connections[] = $pool->getConnection();
        }
    }
}