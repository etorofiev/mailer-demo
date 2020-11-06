<?php

namespace Mailer\Service;

use Mailer\DB;
use Mailer\Model\Field;
use Mailer\Model\Subscriber;

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
}
