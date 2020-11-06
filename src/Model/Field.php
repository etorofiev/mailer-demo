<?php

namespace Mailer\Model;

use JsonSerializable;
use Mailer\DBPool;
use PDO;

class Field implements JsonSerializable
{
    use CommonReadMethods;
    use SerializesToJson;

    private static string $tableName = 'fields';
    private int $id;
    private string $title;
    private string $type;

    public function create()
    {
        if (!empty($this->id)) {
            throw new \LogicException('Cannot create a field with an existing ID');
        }

        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('INSERT INTO fields (title, type) VALUES (:title, :type)');

        $stmt->bindValue(':title', $this->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(':type', $this->getType(), PDO::PARAM_STR);
        $stmt->execute();

        $result = $pdo->lastInsertId();
        $this->id = $result;
        $pool->releaseConnection($connection);
    }

    public function update($fields): int
    {
        if (empty($fields)) {
            throw new \LogicException('Cannot update a field with empty properties');
        }
        if (empty($this->id)) {
            throw new \LogicException('Cannot update a field with a missing ID');
        }
        if (static::hasAllProperties(array_keys($fields)) === false) {
            throw new \LogicException('Cannot update a field with an unknown property');
        }

        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();

        $keys = array_keys($fields);
        $sqlColumnsArray = array_map(fn ($x) => $x . ' = :' . $x, $keys);
        $sqlColumns = implode(', ', $sqlColumnsArray);
        $stmt = $pdo->prepare("UPDATE fields SET $sqlColumns WHERE id = :id");

        $paramFields = array_combine(array_map(fn ($x) => ':' . $x, $keys), $fields);

        foreach ($paramFields as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->rowCount();
        $this->refresh($fields);
        $pool->releaseConnection($connection);

        return $result;
    }

    public function delete()
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('DELETE FROM fields WHERE id = :id');

        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->rowCount();
        $pool->releaseConnection($connection);
        return $result;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
