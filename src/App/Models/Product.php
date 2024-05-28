<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Database;
use Framework\Model;

class Product extends Model
{
    protected $table = "product";
}
