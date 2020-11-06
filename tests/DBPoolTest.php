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
        $dotenv->required([
            'MYSQL_MAX_CONNECTIONS',
            'MYSQL_DB_HOST',
            'MYSQL_DB_PORT',
            'MYSQL_DB_NAME',
            'MYSQL_DB_USER',
            'MYSQL_DB_PASSWORD',
            'MYSQL_DB_CHARSET',
            'DEFAULT_RESULT_LIMIT'
        ]);
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
        $maxConnections = $_ENV['MYSQL_MAX_CONNECTIONS'];
        $pool = DBPool::getInstance();

        for ($i = 0; $i < $maxConnections; $i++) {
            $connections[] = $pool->getConnection();
        }

        $this->assertEquals(5, $pool->getBusy());

        foreach ($connections as $connection) {
            $pool->releaseConnection($connection);
        }
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
