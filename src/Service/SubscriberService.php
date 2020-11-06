<?php

namespace Mailer\Service;

use Mailer\DB;
use Mailer\Model\Field;
use Mailer\Model\Subscriber;
use Mailer\Model\SubscriberField;

class SubscriberService
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function find(int $id): Subscriber
    {
        $subscriber = Subscriber::find($id);
        $subscriberFields = $subscriber->getFieldsRelation();
        $subscriber->fields = [];

        // Perform an eager load of fields to avoid the N+1 issue
        $fieldIds = array_map(fn ($x) => $x->getFieldId(), $subscriberFields);
        $fields = Field::findWhereIn('id', $fieldIds);

        foreach ($subscriberFields as $subField) {
            foreach ($fields as $field) {
                if ($field->getId() === $subField->getFieldId()) {
                    $subField->field = $field;
                    break;
                }
            }

            $subscriber->fields[] = $subField;
        }

        return $subscriber;
    }

    public function attachNewField(Field $field): void
    {
        $count = Subscriber::count();

        if ($count < 1) {
            return;
        }

        $date = new \DateTime();
        $subscribers = Subscriber::get(null, $count);

        foreach ($subscribers as $subscriber) {
            $subField = new SubscriberField();
            $subField->setSubscriberId($subscriber->getId());
            $subField->setFieldId($field->getId());
            $subField->setValue(null);
            $subField->setCreatedAt($date);
            $subField->setUpdatedAt($date);
            $subField->create();
        }
    }
}
