<?php

namespace Mailer;

use PDO;

class DB
{
    private PDO $pdo;
    private int $defaultResultLimit = 0;

    public function __construct()
    {
        $host = $_ENV['MYSQL_DB_HOST'];
        $port = $_ENV['MYSQL_DB_PORT'];
        $db = $_ENV['MYSQL_DB_NAME'];
        $user = $_ENV['MYSQL_DB_USER'];
        $password = $_ENV['MYSQL_DB_PASSWORD'];
        $charset = $_ENV['MYSQL_DB_CHARSET'];
        $this->defaultResultLimit = $_ENV['DEFAULT_RESULT_LIMIT'];

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function getDefaultResultLimit(): int
    {
        return $this->defaultResultLimit;
    }
}