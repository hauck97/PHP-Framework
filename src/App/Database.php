<?php

namespace App;
use PDO;

class Database
{
    public function getConnection(): PDO
    {

        $dsn = "mysql:host=db;dbname=product_db;charset=utf8;port=3306";

        return new PDO($dsn, "product_db_user", "150415", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

    }
}