<?php

    namespace Soukar\Larepo\Services;

    class ClassUtilService
    {
        public static function getClassProperties(string $classsName)
        {
            $cls = new \ReflectionClass($classsName);
            return array_column($cls->getProperties() , 'name');
        }
    }
