<?php

namespace App\Interfaces;

interface DatabaseInterface
{
    public static function getDBConnection() : object;
}
