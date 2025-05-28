<?php

namespace Soukar\Larepo\Interfaces;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Soukar\Larepo\Services\ClassUtilService;

abstract class DataObjectTransfer
{
    abstract static function addRules(FormRequest $request = NULL): array;

    abstract static function updateRules(FormRequest $request = NULL): array;

    public static function fromRequest(FormRequest $request = NULL): DataObjectTransfer
    {
        if ($request) {
            return self::fromArray($request->validated());
        }
        return self::fromArray(request()->all());
    }

    public static function fromArray(array $data): DataObjectTransfer
    {
        $properties = ClassUtilService::getClassProperties(static::class);
        $object = new static;
        foreach ($properties as $property) {

            if (key_exists(
                $property['property'],
                $data
            )) {
                $setterMethod = 'set' . Str::replace(
                        ' ',
                        '',
                        Str::headline($property['property'])
                    );
                // Check if the setter method exists in the object
                if (method_exists(
                    $object,
                    $setterMethod
                )) {
                    // Call the setter method
                    $object->{$setterMethod}($data[$property['property']]);
                } else {
                    // Fallback to direct property assignment
                    $dataValue = $data[$property['property']];
                    if ($property['isDto'] && is_array($dataValue)) {
                        $object->{$property['property']} = $property['type']::fromArray($dataValue);
                    } else {
                        $object->{$property['property']} = $dataValue;
                    }

                }
            }
        }
        return $object;
    }

    public function toArray()
    {
        $values = get_object_vars($this);
        $data = [];
        foreach ($values as $key => $value) {
            if ($value instanceof DataObjectTransfer) {
                $data[$key] = $value->toArray();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
