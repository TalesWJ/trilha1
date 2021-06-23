<?php   

namespace App\Database;

use \PDO;

/**
 * Database Class with usual functions like select, insert, delete...
 */
class Database extends PDO
{
    private static $host;
    private static $name;
    private static $user;
    private static $pass;
    private static $port;
    private $table;

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
    public static function config($host, $name, $user, $pass, $port = 3306)
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
    private function bindKeys($keys) : array
    {
        foreach ($keys as $key) {
            $bindedKeys[$key] = ':' . strtoupper($key);
        }
        return $bindedKeys;
    }

    /**
     * Class constructor
     *
     * @param string $table
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$name . ';port=' . self::$port;
        try {
            parent::__construct($dsn, self::$user, self::$pass);
            $this->setAttribute(parent::ATTR_ERRMODE, parent::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('ERROR: ' .$e->getMessage());
        }
    }

    /**
     * Data Insert 
     * Must be inserted as [column name => value]
     * 
     * @param array $values
     * @return integer
     */
    public function insert($values, $columns) : int
    {
        // Arranging arrays
        array_shift($columns);
        $combinedValues = array_combine($columns, $values);
        $bindedFields = $this->bindKeys(array_values($columns));
        // Building the query
        $query = 'INSERT INTO ' . $this->table . '(' . implode(',', $columns) . ') VALUES (' . implode(',', $bindedFields) . ')';
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
    private function prepareBinds($query, $bindedFields, $values)
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
    public function executeStatement($stmt)
    {
        try {
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $e) {
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
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        // Checking if these informations have been specified
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';
        // Building and executing the query
        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;
        $stmt = $this->prepare($query);
        return $this->executeStatement($stmt);
    }

    /**
     * Updates fields in the database
     *
     * @param string $where
     * @param array $values [column name => value]
     * @return bool
     */
    public function update($where, $values, $columns) : bool
    {
        // Getting array keys
        array_shift($columns);
        $combinedValues = array_combine($columns, $values);
        $bindedFields = $this->bindKeys($columns);
        // Building the query
        foreach ($bindedFields as $key => $value) {
            $updt[$key] = $key . '=' . $value;
        }
        
        $query = 'UPDATE ' . $this->table . ' SET ' . implode(',' , $updt) . ' WHERE ' . $where;
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
    public function delete($where)
    {
        // Building the query
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
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
    public function rows()
    {
        $query = 'SELECT COUNT (*) FROM ' . $this->table . ';';
        $stmt = $this->prepare($query);
        return $this->executeStatement($stmt);
    }

    /**
     * Sets the table attribute
     *
     * @param string $table
     * @return void
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Gets the table attribute
     *
     * @return string
     */
    public function getTable() : string
    {
        return $this->table;
    }
}