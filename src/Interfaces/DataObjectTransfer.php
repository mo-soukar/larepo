<?php

    namespace Soukar\Larepo\Interfaces;

    use Soukar\Larepo\Services\ClassUtilService;

    abstract class DataObjectTransfer
    {
        abstract static function addRules() : array;
        abstract static function updateRules() : array;
        public static function fromRequest() : DataObjectTransfer
        {
            return self::fromArray(request()->all());
        }

        public static function fromArray(array $data) : DataObjectTransfer
        {
            $properties = ClassUtilService::getClassProperties(static::class);
            $object = [];
            foreach ($data as $key=>$value){
                if(in_array($key,$properties))
                    $object[$key] = $value;
            }

            return new static(...$object);
        }

        public function toArray()
        {
            return get_object_vars($this);
        }

    }
