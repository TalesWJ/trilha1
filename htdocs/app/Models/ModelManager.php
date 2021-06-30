<?php

namespace App\Models;

use App\Database\Database;
use App\Interfaces\DatabaseInterface;

class ModelManager
{
    protected static Database $conn;

    public function __construct($conn)
    {
        self::$conn = $conn;
    }
}
