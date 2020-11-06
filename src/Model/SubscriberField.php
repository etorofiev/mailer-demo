<?php

namespace Mailer\Model;

use JsonSerializable;

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
