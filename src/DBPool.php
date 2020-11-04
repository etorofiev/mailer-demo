<?php

namespace Mailer;

class DBPool
{
    private array $connections = [];
    private int $busy = 0;
    private int $max;
    private static DBPool $instance;

    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        } else {
            static::$instance = new static();
            return new static();
        }
    }

    private function __construct()
    {
        $this->max = $_ENV['MYSQL_MAX_CONNECTIONS'];
    }

    public function getBusy()
    {
        return $this->busy;
    }

    public function getConnection(): DB
    {
        $established = $this->busy + count($this->connections);

        if ($established < $this->max) {
            $connection = new DB();
            $this->busy++;
            return $connection;
        } elseif (count($this->connections) > 0 and count($this->connections) < $this->max) {
            $connection = array_pop($this->connections);
            $this->busy++;
            return $connection;
        } else {
            throw new \RuntimeException('No free connections available');
        }
    }

    public function releaseConnection(DB $db): void
    {
        array_push($this->connections, $db);
        $this->busy--;
    }
}