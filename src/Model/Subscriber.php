<?php

namespace Mailer\Model;

use JsonSerializable;
use Mailer\DBPool;
use PDO;

class Subscriber implements JsonSerializable
{
    use CommonReadMethods;
    use SerializesToJson;

    private static string $tableName = 'subscribers';
    private int $id;
    private string $name;
    private string $email;
    private string $state;

    public function create(): void
    {
        if (!empty($this->id)) {
            throw new \LogicException('Cannot create a subscriber with an existing ID');
        }

        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('INSERT INTO subscribers (name, state, email) VALUES (:name, :state, :email)');

        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':state', $this->getState(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->execute();

        $result = $pdo->lastInsertId();
        $this->id = $result;
        $pool->releaseConnection($connection);
    }

    public function update($fields): int
    {
        if (empty($fields)) {
            throw new \LogicException('Cannot update a subscriber with empty properties');
        }
        if (empty($this->id)) {
            throw new \LogicException('Cannot update a subscriber with a missing ID');
        }
        if (static::hasAllProperties(array_keys($fields)) === false) {
            throw new \LogicException('Cannot update a subscriber with an unknown property');
        }

        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();

        $keys = array_keys($fields);
        $sqlColumnsArray = array_map(fn ($x) => $x . ' = :' . $x, $keys);
        $sqlColumns = implode(', ', $sqlColumnsArray);
        $stmt = $pdo->prepare("UPDATE subscribers SET $sqlColumns WHERE id = :id");

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

    public function delete(): int
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('DELETE FROM subscribers WHERE id = :id');

        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->rowCount();
        $pool->releaseConnection($connection);
        return $result;
    }

    public function getFields(): array
    {
        if (empty($fields)) {
            throw new \LogicException('Cannot update a subscriber with empty properties');
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
