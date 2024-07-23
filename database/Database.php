<?php

/**
 * This file contains database actions
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last modified : 28/5/2024
 */

require_once './config/config.php'; // Include the database configuration file
require_once './service/QueryBuilder.php';
class Database extends QueryBuilder
{
    private $dbh;
    private $stmt;
    private $error;

    /**
     * Constructor method.
     * Initializes the database connection.
     */
    public function __construct()
    {
        $dsn = sprintf("mysql:host=%s;port=%s;dbname=%s", host, port,dbname); // Construct the Data Source Name (DSN)
        $options = [
            PDO::ATTR_PERSISTENT => true, // Enable persistent connections
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Set error mode to exceptions
        ];

        try {
            $this->dbh = new PDO($dsn, user, pass, $options); // Create a new PDO instance
        } catch (PDOException $e) {
            error_log($e->getMessage()); //Logs error
        }
    }
    
    /**
     * Prepare a SQL query.
     *
     * @param string $sql
     *
     * @return void
     */
    public function query(string $sql): void
    {
    $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Execute a prepared statement.
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    /**
     * Return a result set as an array of objects.
     *
     * @return array|false
     */
    // public function resultSet(): array | false
    // {
    //     $this->execute();
    //     return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    // }
    public function resultSet($fetchMode = PDO::FETCH_OBJ): array {
        $this->execute();
        return $this->stmt->fetchAll($fetchMode);
    }

    /**
     * Return a single row as an object.
     *
     * @return object|false
     */

    public function single($fetchMode = PDO::FETCH_OBJ): ?object {
        $this->execute();
        $result = $this->stmt->fetch($fetchMode);
        return $result !== false ? $result : null;
    }
    

    /**
     * Get the number of rows affected by the last SQL statement.
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    /**
     * Alias of rowCount().
     *
     * @return int
     */
    public function affected_rows(): int
    {
        return $this->stmt->rowCount();
    }

    /**
     * Get the SQLSTATE error code associated with the last operation on the statement handle.
     *
     * @return string
     */
    public function errorCode(): string
    {
        return $this->stmt->errorCode();
    }

    /**
     * Dynamically delete rows from db
     *
     * @param $table string
     * @param $condition array
     *
     * @return bool
     */
    public function delete(string $table, array $condition): bool
    {
        $query = "DELETE FROM $table ";
        if (is_array($condition)) {
            $query = $query . $this->arrayToCondition($condition);
        }
        $this->query($query);
        try {
            $this->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $this->affected_rows();
    }

    /**
     * Dynamically retrive rows from db
     *
     * @param $table string
     * @param $condition array
     *
     * @return bool|object
     */
    public function get(string $table, array $condition, array $columns): ?object {
        if (!empty($columns)) {
            $query = "SELECT " . $this->arrayToSelect($columns) . " FROM $table ";
        } else {
            $query = "SELECT * FROM $table ";
        }
        if (!empty($condition)) {
            $query .= $this->arrayToCondition($condition);
        }
        $this->query($query);
        try {
            $this->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
        $result = $this->single();
        return $result !== false ? $result : null;
    }
    
    /**
     * Get all records from a table based on conditions
     *
     * @param string $table The table name
     * @param array $condition The condition to filter the records
     * @param array $columns The columns to be selected
     *
     * @return array The result set
     */
    public function getAll(string $table, array $condition, array $columns): array
    {
        $query = "SELECT " . ($columns ? $this->arrayToColumns($columns) : '*') . " FROM $table ";
        $query .= $condition ? $this->arrayToCondition($condition) : '';
        $this->query($query);
        try {
            $this->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $this->resultSet();
    }

    /**
     * Dynamically retrive rows from db
     *
     * @param $table string
     * @param $data array
     *
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $query = "INSERT INTO $table ";
        if (is_array($data)) {
            $query = $query . $this->arrayToInsert($data);
        }
        $this->query($query);
        try {
            $this->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $this->affected_rows();
    }

    /**
     * Dynamically update rows from db
     *
     * @param $table string
     * @param $condition array
     * @param $data array
     *
     * @return bool
     */
    public function update(string $table, array $data, array $condition): bool
    {
        $query = "UPDATE $table ";
        if (is_array($data)) {
            $query = $query . $this->setValues($data);
        } else {
            return false;
        }
        if (is_array($condition)) {
            $query = $query . $this->arrayToCondition($condition);
        }
        $this->query($query);
        try {
            $this->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $this->affected_rows();
    }

    /**
     * Bind values to parameters in a prepared statement.
     *
     * @param string $param The parameter placeholder to bind the value to
     * @param mixed $value The value to bind
     * @param int $type Optional data type for the parameter (e.g., PDO::PARAM_INT)
     * @return void
     */
    public function bind(string $param, $value, $type = null): void
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

     
    /**
     * Insert a record into a table and return the last inserted ID.
     * 
     * @param string $table The name of the table.
     * @param array $data An associative array of column => value pairs.
     * @return int|false Returns the last inserted ID on success, false on failure.
     */
    public function insertWithLastId(string $table, array $data): int|false {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->dbh->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->execute();
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Insert a record into a table.
     * 
     * @param string $table The name of the table.
     * @param array $data An associative array of column => value pairs.
     * @return bool Returns true on success, false on failure.
     */
    public function insertt(string $table, array $data): bool {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->dbh->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function gett(string $table, array $conditions, array $columns): object|false {
        $columns_str = $columns ? implode(", ", $columns) : '*';
        $conditions_str = implode(" AND ", array_map(fn($k) => "$k = :$k", array_keys($conditions)));
        $sql = "SELECT $columns_str FROM $table WHERE $conditions_str";
        $this->query($sql);
        foreach ($conditions as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
}
