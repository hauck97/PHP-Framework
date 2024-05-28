<?php

declare(strict_types=1);

namespace Framework;
use App\Database;
use PDO;

abstract class Model
{
    protected $table;
    public function __construct(private Database $database)
    {
    }
    public function findAll(): array
    {

        $pdo = $this->database->getConnection();

        $statement = "SELECT * 
                      FROM {$this->table}";

        $stmt = $pdo->query($statement);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function find(string $id): array|bool
    {
        $pdo = $this->database->getConnection();

        $statement =    "SELECT * 
                         FROM {$this->table} 
                         WHERE id = :id";

        $stmt = $pdo->prepare($statement);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
