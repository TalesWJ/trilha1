<?php   

namespace App\Database;

use App\Interfaces\DatabaseInterface;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Database Class with usual functions like select, insert, delete...
 */
class Database extends PDO implements DatabaseInterface
{
    private static string $host;
    private static string $name;
    private static string $user;
    private static string $pass;
    private static int $port;
    private static $conn;
    private static string $table;

    /**
     * Config Function
     *
     * @param string $host
     * @param string $name
     * @param string $user
     * @param string $pass
     * @param integer $port
     * @return void
     */
    public static function config(string $host, string $name, string $user, string $pass, int $port = 3306) : void
    {
        self::$host = $host;
        self::$name = $name;
        self::$user = $user;
        self::$pass = $pass;
        self::$port = $port;
    }

    /**
     * Binds the keys for insertion
     *
     * @param array $keys
     * @return array
     */ 
    private function bindKeys(array $keys) : array
    {
        $bindedKeys = [];
        foreach ($keys as $key) {
            $bindedKeys[$key] = ':' . strtoupper($key);
        }
        return $bindedKeys;
    }

    /**
     * GetDBConnection
     * @return object
     */
    public static function getDBConnection() : object
    {
        return self::$conn;
    }

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$name . ';port=' . self::$port;
        try {
            self::$conn = parent::__construct($dsn, self::$user, self::$pass);
            $this->setAttribute(parent::ATTR_ERRMODE, parent::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' .$e->getMessage());
        }
    }

    /**
     * Data Insert 
     * Must be inserted as [column name => value]
     * 
     * @param array $values
     * @param array $columns
     * @return integer
     */
    public function insert(array $values,array $columns) : int
    {
        // Arranging arrays
        array_shift($columns);
        $combinedValues = array_combine($columns, $values);
        $bindedFields = $this->bindKeys(array_values($columns));
        // Building the query
        $query = 'INSERT INTO ' . self::$table . '(' . implode(',', $columns) . ') VALUES (' . implode(',', $bindedFields) . ')';
        // Prepares the query
        $stmt = $this->prepareBinds($query, $bindedFields, $combinedValues);
        // Executes the query and returns the last inserted ID
        $this->executeStatement($stmt);
        return $this->lastInsertId();
    }

    /**
     * Replaces the values in the query with the values supplied
     *
     * @param string $query
     * @param array $bindedFields
     * @param array $values
     * @return PDOStatement
     */
    private function prepareBinds(string $query,array $bindedFields,array $values) : PDOStatement
    {
        $stmt = $this->prepare($query);
        foreach ($bindedFields as $field => $value) {
            $stmt->bindParam($value, $values[$field]);
        }
        return $stmt;
    }

    /**
     * Executes the query with the parameters passed
     *
     * @param PDOStatement $stmt
     * @return PDOStatement
     */
    public function executeStatement(PDOStatement $stmt) : PDOStatement
    {
        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Returns data from the database
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public function select(string $where = '', string $order = '', string $limit = '', string $fields = '*') : PDOStatement
    {
        // Checking if these informations have been specified
        $where = ($where!=='') ? 'WHERE ' . $where : '';
        $order = ($order!=='') ? 'ORDER BY ' . $order : '';
        $limit = ($limit!=='') ? 'LIMIT ' . $limit : '';
        // Building and executing the query
        $query = 'SELECT ' . $fields . ' FROM ' . self::$table . ' ' . $where . ' ' . $order . ' ' . $limit;
        $stmt = $this->prepare($query);
        return $this->executeStatement($stmt);
    }

    /**
     * Updates fields in the database
     *
     * @param string $where
     * @param array $values
     * @param array $columns
     * @return bool
     */
    public function update(string $where, array $values, array $columns) : bool
    {
        // Getting array keys
        array_shift($columns);
        $combinedValues = array_combine($columns, $values);
        $bindedFields = $this->bindKeys($columns);
        $updt = [];
        // Building the query
        foreach ($bindedFields as $key => $value) {
            $updt[$key] = $key . '=' . $value;
        }
        
        $query = 'UPDATE ' . self::$table . ' SET ' . implode(',' , $updt) . ' WHERE ' . $where;
        // Executes the query and returns success if query was executed
        $stmt = $this->prepareBinds($query, $bindedFields, $combinedValues);
        $this->executeStatement($stmt);
        return true;
    }

    /**
     * Deletes rows in database
     *
     * @param string $where
     * @return bool
     */
    public function delete(string $where) : bool
    {
        // Building the query
        $query = 'DELETE FROM ' . self::$table . ' WHERE ' . $where;
        $stmt = $this->prepare($query);
        // Executes the query and returns success if query was executed
        $this->executeStatement($stmt);
        return true;
    }

    /**
     * Returns the number of rows in the selected tables
     *
     * @return PDOStatement
     */
    public function rows() : PDOStatement
    {
        $query = 'SELECT COUNT (*) FROM ' . self::$table . ';';
        $stmt = $this->prepare($query);
        return $this->executeStatement($stmt);
    }

    /**
     * Sets the table attribute
     *
     * @param string $table
     */
    public function setTable(string $table) : void
    {
        self::$table = $table;
    }

    /**
     * Gets the table attribute
     *
     * @return string
     */
    public function getTable() : string
    {
        return self::$table;
    }
}