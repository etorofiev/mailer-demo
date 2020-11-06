<?php

namespace Mailer\Model;

use JsonSerializable;
use Mailer\DBPool;
use PDO;

class SubscriberField implements JsonSerializable
{
    use CommonReadMethods;
    use SerializesToJson;

    private static string $tableName = 'subscribers_fields';
    private int $id;
    private int $subscriber_id;
    private int $field_id;
    private ?string $value;
    private string $created_at;
    private string $updated_at;

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
        $stmt = $pdo->prepare("UPDATE subscribers_fields SET $sqlColumns WHERE id = :id");

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSubscriberId(): int
    {
        return $this->subscriber_id;
    }

    /**
     * @param int $subscriber_id
     */
    public function setSubscriberId(int $subscriber_id): void
    {
        $this->subscriber_id = $subscriber_id;
    }

    /**
     * @return int
     */
    public function getFieldId(): int
    {
        return $this->field_id;
    }

    /**
     * @param int $field_id
     */
    public function setFieldId(int $field_id): void
    {
        $this->field_id = $field_id;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        ////TODO move the date format to a common shared place
        return \DateTime::createFromFormat('Y-m-d H:i:s', $this->created_at);
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $this->updated_at);
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at->format('Y-m-d H:i:s');
    }
}
