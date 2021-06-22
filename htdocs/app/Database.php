<?php   

namespace App;

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
    public function insert($values) : int
    {
        // Getting array keys
        $fields = array_keys($values);
        // Creating an ? array
        $binds = array_pad([], count($fields),  '?' );
        // Building the query
        $query = 'INSERT INTO ' . $this->table . '(' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';
        // Executes the query and returns the last inserted ID
        $this->execute($query, array_values($values));
        return $this->lastInsertId();
    }

    /**
     * Executes the query with the parameters passed
     *
     * @param string $query
     * @param string $values
     * @return PDOStatement
     */
    public function execute($query, $values = [])
    {
        try {
            $stmt = $this->prepare($query);
            $stmt->execute($values);
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
        return $this->execute($query);
    }

    /**
     * Updates fields in the database
     *
     * @param string $where
     * @param array $values [column name => value]
     * @return bool
     */
    public function update($where, $values) : bool
    {
        // Getting array keys
        $fields = array_keys($values);
        // Building the query
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;
        // Executes the query and returns success if query was executed
        $this->execute($query, array_values($values));
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
        // Executes the query and returns success if query was executed
        $this->execute($query);
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
        return $this->execute($query);
    }

}