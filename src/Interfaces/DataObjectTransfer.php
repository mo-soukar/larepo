<?php

namespace Soukar\Larepo\Interfaces;

use Soukar\Larepo\Services\ClassUtilService;
use Illuminate\Support\Str;

abstract class DataObjectTransfer
{
    abstract static function addRules(): array;
    abstract static function updateRules(): array;
    public static function fromRequest(): DataObjectTransfer
    {
        return self::fromArray(request()->all());
    }

    public static function fromArray(array $data): DataObjectTransfer
    {
        $properties = ClassUtilService::getClassProperties(static::class);

        $object = new static;
        foreach ($properties as $property) {
            if (key_exists($property, $data)) {
                $setterMethod = 'set' . Str::replace(' ', '', Str::headline($property));
                // Check if the setter method exists in the object
                if (method_exists($object, $setterMethod)) {
                    // Call the setter method
                    $object->{$setterMethod}($data[$property]);
                } else {
                    // Fallback to direct property assignment
                    $object->{$property} = $data[$property];
                }
            }
        }

        return $object;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
