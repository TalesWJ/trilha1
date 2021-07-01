<?php

namespace App\Interfaces;

interface CoreModelInterface
{
    public static function selectDataByColumn(string $column, string $value);

    public static function selectAllData();

    public static function insertData(array $data) : int;

    public static function updateData(array $data, string $column, string $value) : bool;

    public static function deleteData(string $column, string $value) : bool;
}
