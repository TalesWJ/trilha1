<?php

namespace App\Models;

use App\Models\Interfaces\CoreModelInterface;
use App\Database\Database;
use Magento\Theme\Model\Design\Config\Plugin\Dump;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class CoreModel implements CoreModelInterface
{

    private $connection;
    private $table;
    private $columns;

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
     * @return array
     */
    public function selectDataById(int $id)
    {
        $where = $this->columns[0] . '=' . $id;
        $data = $this->connection->select($where);
        $obj = $data->fetchAll(Database::FETCH_OBJ);
        return $obj;
    }

    /**
     * Retrieves all data from DB
     *
     * @return array
     */
    public function selectAllData()
    {
        $data = $this->connection->select();
        return $data->fetchAll(Database::FETCH_OBJ);
    }

    /**
     * Insert data in the DB
     *
     * @param array $data
     * @return integer
     */
    public function insertData(array $data) : int
    {
        $id = $this->connection->insert($data, $this->columns);
        return $id; 
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
        $cond = $this->connection->update($where, $data, $this->columns);
        return $cond;
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
        $cond = $this->connection->delete($where);
        return $cond;
    }

}