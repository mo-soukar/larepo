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
        foreach ($data as $key => $value) {
            if (in_array($key, $properties)) {

                $setterMethod = 'set' . Str::replace(' ', '', Str::headline($key));
                // Check if the setter method exists in the object
                if (method_exists($object, $setterMethod)) {
                    // Call the setter method
                    $object->{$setterMethod}($value);
                } else {
                    // Fallback to direct property assignment
                    $object->{$key} = $value;
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
