<?php

declare(strict_types=1);

set_error_handler(function(
    int $errorNumber,
    string $errorString,
    string $errorFile,
    int $errorLine
): bool
{
   throw new ErrorException($errorString, 0, $errorNumber, $errorFile, $errorLine);
});

set_exception_handler(function(Throwable $exception) {

    $show_errors = true;

    if ($show_errors) {

        ini_set("display_errors", "on");

    } else {

        ini_set("display_errors", "off");

        require "./../views/500.php";

    }

    throw $exception;
});

$path = parse_url("$_SERVER[REQUEST_URI]", PHP_URL_PATH);

if ($path === false) {
    throw new UnexpectedValueException("Malformed URL:
                                       '{$_SERVER["REQUEST_URI"]}'");
}

spl_autoload_register(function (string $class_name) {
    require "./../" . str_replace("\\", "/" , $class_name) . ".php";
});

$router = new Framework\Router;

$router->add("/admin/{controller}/{action}", ["namespace" => "Admin"]);
$router->add("/{title}/{id:\d+}/{page:\d+}", ["controller" => "products", "action" => "showPage"]);
$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("/{controller}/{id:[0-9]+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");

$container = new Framework\Container();

$container->set(App\Database::class, function() {

    return new App\Database("172.18.0.2", "product_db", "product_db_user", "150415");

});

$dispatcher = new Framework\Dispatcher($router, $container);

$dispatcher->handle($path);