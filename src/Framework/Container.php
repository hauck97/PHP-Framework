<?php

namespace Framework;
use ReflectionClass;
 class Container
 {
     private array $registry = [];

     public function set(string $name, $value): void
     {
         $this->registry[$name] = $value;
     }

     public function getDeps(string $class_name): object
     {
         if (array_key_exists($class_name, $this->registry)) {
             return $this->registry[$class_name];
         }

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