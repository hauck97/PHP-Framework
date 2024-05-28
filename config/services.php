<?php

$container = new Framework\Container();

$container->set(App\Database::class, function() {

    return new App\Database("172.18.0.2", "product_db", "product_db_user", "150415");

});

return $container;

