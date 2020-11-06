<?php

namespace Mailer\Model;

use Mailer\DBPool;
use PDO;

trait CommonReadMethods
{
    public static function fromArray(array $values)
    {
        $obj = new static();

        foreach ($values as $key => $value) {
            if (property_exists(static::class, $key)) {
                $method = 'set' . ucfirst($key);
                $obj->$method($value);
            }
        }

        return $obj;
    }

    public static function hasAllProperties(array $keys)
    {
        foreach ($keys as $property) {
            if (property_exists(static::class, $property) === false) {
                return false;
            }
        }

        return true;
    }

    public function refresh($values)
    {
        // Unfortunately PDO::FETCH_INTO does not update private/protected properties,
        // so we can either manually update all properties, or return a new instance
        // Pick your poison below

        /*
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM ' . static::$tableName . ' WHERE id = :id');

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $result = $stmt->fetch();
        $pool->releaseConnection($connection);

        return $result;
        */

        if (static::hasAllProperties(array_keys($values)) === false) {
            throw new \LogicException('Cannot update a ' . static::$tableName . ' with an unknown property');
        }

        foreach ($values as $key => $value) {
            if (property_exists(static::class, $key)) {
                $method = 'set' . ucfirst($key);
                $this->$method($value);
            }
        }
    }

    public static function find(int $id)
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM ' . static::$tableName . ' WHERE id = :id');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchObject(self::class);
        $pool->releaseConnection($connection);
        return $result;
    }

    public static function findBy(string $key, $value)
    {
        if (!property_exists(static::class, $key)) {
            throw new \UnexpectedValueException('Property does not exist');
        }

        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM " . static::$tableName . " WHERE $key = :value");

        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchObject(static::class);
        $pool->releaseConnection($connection);
        return $result;
    }

    public static function findWhereIn(string $key, $values)
    {
        if (!property_exists(static::class, $key)) {
            throw new \UnexpectedValueException('Property does not exist');
        }

        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $in  = str_repeat('?,', count($values) - 1) . '?';
        $stmt = $pdo->prepare("SELECT * FROM " . static::$tableName . " WHERE $key IN ($in)");

        $stmt->execute($values);

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
        $pool->releaseConnection($connection);
        return $results;
    }

    public static function get(int $from = null, int $limit = null)
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM ' . static::$tableName . ' WHERE id > :from LIMIT :limit');

        if (is_null($limit) or $limit <= 0) {
            $limit = $connection->getDefaultResultLimit();
        }
        if (is_null($from) or $from <= 0) {
            $from = 0;
        }

        $stmt->bindParam(':from', $from, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
        $pool->releaseConnection($connection);
        return $results;
    }

    public static function count()
    {
        $pool = DBPool::getInstance();
        $connection = $pool->getConnection();
        $pdo = $connection->getPdo();
        $stmt = $pdo->query('SELECT COUNT(*) FROM ' . static::$tableName);

        $results = $stmt->fetchColumn();
        $pool->releaseConnection($connection);
        return $results;
    }
}
