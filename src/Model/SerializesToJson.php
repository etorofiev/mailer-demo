<?php

namespace Mailer\Model;

trait SerializesToJson
{
    public function jsonSerialize()
    {
        $props = get_object_vars($this);
        $collection = [];

        foreach ($props as $name => $value) {
            $methodToCheck = 'get' . ucfirst($name);

            if (method_exists($this, $methodToCheck)) {
                $collection[$name] = $this->$methodToCheck();
            }
        }

        return $collection;
    }
}