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
            } elseif (is_array($value) and isset($value[0]) and $value[0] instanceof \JsonSerializable) {
                $collection[$name] = $value;
            } elseif ($value instanceof \JsonSerializable) {
                $collection[$name] = $value;
            }
        }

        return $collection;
    }
}