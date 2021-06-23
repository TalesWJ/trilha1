<?php

namespace App\Models\Interfaces;

interface CoreModelInterface
{
    public function selectDataById(int $id) : array;

    public function selectAllData() : array;

    public function insertData(array $data) : int;

    public function updateData(array $data, string $where, int $id) : bool;

    public function deleteData(string $column, string $value) : bool;
}
