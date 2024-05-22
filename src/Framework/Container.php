<?php

namespace Framework;
use ReflectionClass;
 class Container
 {
     public function getDeps(string $class_name): object
     {
         $reflection = new ReflectionClass($class_name);

         $reflectionConstructor = $reflection->getConstructor();

         $reflectedDependencies = [];

         if ($reflectionConstructor === null) {

             return new $class_name;

         }

         foreach ($reflectionConstructor->getParameters() as $parameter) {

             $type = (string) $parameter->getType();

             $reflectedDependencies[] = $this->getDeps($type);

         }

         return new $class_name(...$reflectedDependencies);
     }
 }