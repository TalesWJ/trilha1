<?php

namespace App\Models;

use Exception;
use App\Interfaces\CoreModelInterface;
use App\Database\Database;
use App\DependencyInjection\Builder;

class CoreModel extends ModelManager implements CoreModelInterface
{

    private static string $table;
    private static array $columns;

    /**
     * Class Construct
     *
     * @param string $table
     * @param array $columns
     */
    public static function setAttributes(string $table, array $columns) : void
    {
        self::$table = $table;
        self::$columns = $columns;
    }

    /**
     * Retrieves data from DB based on the given Column and Value
     *
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public static function selectDataByColumn(string $column, string $value) : array
    {
        Builder::buildContainer()->get('ModelManager');

        try {
            if (!in_array($column, self::$columns, TRUE)) {
                throw new Exception("Coluna $column não encontrada");
            }
            self::$conn->setTable(self::$table);
            $where = $column . '="' . $value . '"';
            return self::$conn->select($where)->fetchAll(Database::FETCH_OBJ);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Retrieves all data from DB
     *
     * @return array
     */
    public static function selectAllData() : array
    {
        Builder::buildContainer()->get('ModelManager');
        self::$conn->setTable(self::$table);
        return self::$conn->select()->fetchAll(Database::FETCH_OBJ);
    }

    /**
     * Insert data in the DB
     *
     * @param array $data
     * @return integer
     */
    public static function insertData(array $data) : int
    {
        self::$conn->setTable(self::$table);
        return self::$conn->insert($data, self::$columns);
    }

    /**
     * Updates data in the DB based on ID
     *
     * @param array $data
     * @param string $column
     * @param string $value
     * @return boolean
     */
    public static function updateData(array $data, string $column, string $value) : bool
    {
        Builder::buildContainer()->get('ModelManager');

        try {
            if (!in_array($column, self::$columns, TRUE)) {
                throw new Exception("Coluna $column não encontrada");
            }
            $where = $column . '="' . $value . '"';
            self::$conn->setTable(self::$table);
            return self::$conn->update($where, $data, self::$columns);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Deletes data in the DB based on [column => value]
     *
     * @param string $column
     * @param string $value
     * @return boolean
     */
    public static function deleteData(string $column, string $value) : bool
    {
        $where = $column . '=' . $value;
        self::$conn->setTable(self::$table);
        return self::$conn->delete($where);
    }

}