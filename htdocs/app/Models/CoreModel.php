<?php

namespace App\Models;

use App\Models\Interfaces\CoreModelInterface;
use App\Database\Database;

class CoreModel implements CoreModelInterface
{

    private Database $connection;
    private string $table;
    private array $columns;

    /**
     * Class Construct
     *
     * @param string $table
     * @param array $columns
     */
    public function __construct(string $table, array $columns)
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->connection = new Database($this->table);
    }

    /**
     * Retrieves data from DB based on the ID
     *
     * @param integer $id
     * @return mixed
     */
    public function selectDataById(int $id) : mixed
    {
        $where = $this->columns[0] . '=' . $id;
        return $this->connection->select($where)->fetchAll(Database::FETCH_OBJ);
    }

    /**
     * Retrieves all data from DB
     *
     * @return array
     */
    public function selectAllData() : mixed
    {
        return $this->connection->select()->fetchAll(Database::FETCH_OBJ);
    }

    /**
     * Insert data in the DB
     *
     * @param array $data
     * @return integer
     */
    public function insertData(array $data) : int
    {
        return $this->connection->insert($data, $this->columns);
    }

    /**
     * Updates data in the DB based on ID
     *
     * @param array $data
     * @param integer $id
     * @return boolean
     */
    public function updateData(array $data, int $id) : bool
    {
        $where = $this->columns[0] . '=' . $id;
        return $this->connection->update($where, $data, $this->columns);
    }

    /**
     * Deletes data in the DB based on [column => value]
     *
     * @param string $column
     * @param string $value
     * @return boolean
     */
    public function deleteData(string $column, string $value) : bool
    {
        $where = $column . '=' . $value;
        return $this->connection->delete($where);
    }

}