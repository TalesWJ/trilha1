<?php

namespace App\Models\Interfaces;

interface CoreModelInterface
{
    public function selectDataById(int $id);

    public function selectAllData();

    public function insertData(array $data) : int;

    public function updateData(array $data, int $id) : bool;

    public function deleteData(string $column, string $value) : bool;
}
