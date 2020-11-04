<?php

namespace Mailer\Model;

use Mailer\DBPool;
use PDO;

class Field
{
    private string $title;
    private string $type;

    public static function find(int $id)
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM fields WHERE id = :id');

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
        $stmt = $pdo->prepare('SELECT * FROM fields WHERE id > :from LIMIT :limit');

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}