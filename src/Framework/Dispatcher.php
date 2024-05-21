<?php

namespace Framework;

use ReflectionMethod;
use ReflectionClass;

class Dispatcher
{
    public function __construct(private Router $router)
    {
    }

    public function handle(string $path)
    {
        $url_parameters = $this->router->match($path);

        if ($url_parameters === false) {
            exit("No route matched.");
        }

        $action = $this->getMethodName($url_parameters);
        $controller = $this->getControllerName($url_parameters);

        $controller_object = $this->getDependencies($controller);

        $arguments = $this->getActionArguments($controller, $action, $url_parameters);
        $controller_object->$action(...$arguments);

    }

    private function getActionArguments(string $controller, string $action, array $parameters): array
    {
        $arguments = [];
        $method = new ReflectionMethod($controller, $action);

        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName();
            $arguments[$name] = $parameters[$name];
        };

        return $arguments;
    }

    private function getControllerName(array $parameters): string
    {

        $controller = $parameters["controller"];
        $controller = str_replace("-", "", ucwords(strtolower($controller), "-"));
        $namespace = "App\Controllers";

        if (array_key_exists("namespace", $parameters)) {

            $namespace .= "\\" . $parameters["namespace"];

        }

        return $namespace . "\\" .$controller;

    }

    private function getMethodName(array $parameters): string
    {

        $action = $parameters["action"];
        return str_replace("-", "", lcfirst(ucwords(strtolower($action), "-")));

    }

    private function getDependencies(string $class_name): object
    {
        $reflection = new ReflectionClass($class_name);

        $reflectionConstructor = $reflection->getConstructor();

        $reflectedDependencies = [];

        if ($reflectionConstructor === null) {

            return new $class_name;

        }

        foreach ($reflectionConstructor->getParameters() as $parameter) {

            $type = (string) $parameter->getType();

            $reflectedDependencies[] = $this->getDependencies($type);

        }

        return new $class_name(...$reflectedDependencies);
    }
}