<?php

namespace Mailer\Model;

use Mailer\DBPool;
use PDO;

class Subscriber
{
    private string $name;
    private string $email;
    private string $state;

    public static function find(int $id)
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM subscribers WHERE id = :id');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        $pool->releaseConnection($connection);
        return $result;
    }

    public static function get(int $from = null, int $limit = null)
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM subscribers WHERE id > :from LIMIT :limit');

        if (is_null($limit) or $limit <= 0) {
            $limit = $connection->getDefaultResultLimit();
        }
        if (is_null($from) or $from <= 0) {
            $from = 0;
        }

        $stmt->bindParam(':from', $from, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $pool->releaseConnection($connection);
        return $results;
    }

    public static function count()
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->query('SELECT COUNT(*) FROM subscribers');

        $results = $stmt->fetchColumn();
        $pool->releaseConnection($connection);
        return $results;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }
}