<?php

declare(strict_types=1);

namespace Framework;
use ReflectionClass;
use Closure;
use ReflectionNamedType;

 class Container
 {
     private array $registry = [];

     public function set(string $name, Closure $value): void
     {
         $this->registry[$name] = $value;
     }

     public function getDeps(string $class_name): object
     {
         if (array_key_exists($class_name, $this->registry)) {
             return $this->registry[$class_name]();
         }

         $reflection = new ReflectionClass($class_name);

         $reflectionConstructor = $reflection->getConstructor();

         $reflectedDependencies = [];

         if ($reflectionConstructor === null) {

             return new $class_name;

         }

         foreach ($reflectionConstructor->getParameters() as $parameter) {

             $type = $parameter->getType();

             if ($type == null) {

                 exit("Constructor parameter '{$parameter->getName()}'
                 in the $class_name class
                 has no type declaration");

             }

             if ( ! ($type instanceof ReflectionNamedType)) {

                 exit("Constructor parameter '{$parameter->getName()}'
                    in the $class_name class is an invalid type: '$type'
                     - only single named types supported");

             }

             if ($type->isBuiltin()) {

                 exit("Unable to resolve constructor parameter 
                        '{$parameter->getName()}'
                        of type '$type' in the $class_name class");

             }

             $reflectedDependencies[] = $this->getDeps((string) $type);

         }

         return new $class_name(...$reflectedDependencies);
     }
 }