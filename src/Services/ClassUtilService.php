<?php

namespace Soukar\Larepo\Services;

use Soukar\Larepo\Interfaces\DataObjectTransfer;

class ClassUtilService
{
    public static function propertyIsDto($property): bool
    {
        $type = $property->getType();
        if ($type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();
            if (class_exists($typeName)) {
                $reflectionTypeClass = new \ReflectionClass($typeName);
                if ($reflectionTypeClass->isSubclassOf(
                        DataObjectTransfer::class
                    ) || $typeName === DataObjectTransfer::class) {
                    return true;
                }
            }

        }
        return false;
    }

    public
    static function getClassProperties(string $classsName)
    {
        $cls = new \ReflectionClass($classsName);
        $properties = [];
        foreach ($cls->getProperties() as $property) {
            $properties[] = [
                'property' => $property->getName(),
                'type'     => $property->getType()?->getName(),
                'isDto'    => self::propertyIsDto($property),
            ];
        }
        return $properties;
    }
}
