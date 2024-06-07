<?php
$root = __DIR__ . "/..";
require_once("$root/config.php");

abstract class Model {
    protected $table;

    public function getTable() {
        return $this->table;
    }

    /**
     * Executes a parameterized query using MySQLi.
     *
     * @param string $sql The SQL query with placeholders for parameters.
     * @param mixed $param The parameter to bind to the SQL query.
     * @return mixed Returns the result set on success, or false on failure.
     */
    protected function mysqliParametrizedQuery($sql, $param) {
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        if ($mysqli->connect_error) {
            return false;
        }

        if(!$stmt = $mysqli->prepare($sql)) {
            return false;
        }
        $paramType = $this->getType($param);
        if(!$stmt->bind_param($paramType, $param)) {
            return false;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();
        return $result;
    }

    protected function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?;";

        $result = $this->mysqliParametrizedQuery($sql, $id);

        return $result;
    }

    /**
     * Insert a row in the database.
     *
     * @param array $data Array of associative arrays, each containing column names and values to insert.
     * @param string $db Database to be used. Defaults to config value.
     * @return int|bool Returns last inserted id on success, false on failure.
     */
    protected function insert(array $data, string $db = __DATABASE__) {
        if (empty($data)) {
            return false;
        }

        // Check if $data is a single associative array
        if (array_keys($data) !== range(0, count($data) - 1)) {
            $data = [$data];
        }

        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, $db);

        if ($mysqli->connect_error) {
            return false;
        }

        $columnsList = [];
        $types = '';
        $values = [];
        $placeholders = [];
        $columnsList = implode(", ", array_keys($data[0]));

        // Iterate through each of the associative arrays.
        foreach ($data as $row) {
            $rowPlaceholders = [];
            // Iterate through each of the key-value pairs.
            foreach ($row as /* $column => */ $value) {
                $rowPlaceholders[] = '?';
                $types .= $this->getType($value);
                $values[] = $value;
            }
            $placeholders[] = '(' . implode(", ", $rowPlaceholders) . ')';
        }
    
        $placeholdersList = implode(", ", $placeholders);
    
        // Combine into the full SQL statement
        $sql = "INSERT INTO $this->table ($columnsList) VALUES $placeholdersList";

        if (!($stmt = $mysqli->prepare($sql))) {
            $mysqli->close();
            return false;
        }

        // Bind the params
        $stmt->bind_param($types, ...$values);

        $stmt->execute();
        // Get the last insert id
        $inserted_id = $mysqli->insert_id;

        $stmt->close();
        $mysqli->close();
        return $inserted_id;
    }

    /**
     * Update a row in the database.
     *
     * @param int $id Id of the element to be updated.
     * @param array $data Associative array of column names and values to update.
     * @param string $db Database to be used. Defaults to config value.
     * @return bool Returns true on success, false on failure.
     */
    protected function update(int $id, array $data, string $db = __DATABASE__) {
        if ($id <= 0) {
            throw new InvalidArgumentException("id must be greater than 0.\n");
        }

        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, $db);

        if ($mysqli->connect_error) {
            return false;
        }

        $id = intval($id);
        // Construct the SET part of the SQL statement
        $setPart = [];
        $types = '';
        $values = [];
        foreach ($data as $column => $value) {
            $setPart[] = "$column = ?";
            $types .= $this->getType($value);
            $values[] = $value;
        }
        $setPart = implode(', ', $setPart);

        // Combine into the full SQL statement
        $sql = "UPDATE $this->table SET $setPart WHERE id = ?";

        // Concat 'i'
        $types .= 'i'; // type for the id
        $values[] = $id;

        if (!($stmt = $mysqli->prepare($sql))) {
            $mysqli->close();
            return false;
        }

        // Bind the params
        $stmt->bind_param($types, ...$values);

        $result = $stmt->execute();
    
        $stmt->close();
        $mysqli->close();
        return $result;
    }

    /**
     * Delete a row in the database.
     *
     * @param int $id Id of the element to be deleted.
     * @param string $db Database to be used. Defaults to config value.
     * @return bool Returns true on success, false on failure.
     */
    protected function delete(int $id, string $db = __DATABASE__) {
        if ($id <= 0) {
            throw new InvalidArgumentException("id must be greater than 0.\n");
        }

        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, $db);

        if ($mysqli->connect_error) {
            return false;
        }

        $id = intval($id);

        // Combine into the full SQL statement
        $sql = "DELETE FROM $this->table WHERE id = ?";

        if (!($stmt = $mysqli->prepare($sql))) {
            $mysqli->close();
            return false;
        }

        // Bind the params
        $stmt->bind_param("i", $id);

        $result = $stmt->execute();
    
        $stmt->close();
        $mysqli->close();
        return $result;
    }

    protected function getAll($table, $limit) {
        $sql = "SELECT * FROM $table LIMIT ?;";
        $result = $this->mysqliParametrizedQuery($sql, $limit);
        return $result;
    }

    // Helper function to determine the type of the value for mysqli bind_param
    protected function getType($value) {
        switch (gettype($value)) {
            case 'integer':
                return 'i';
            case 'double':
                return 'd';
            case 'string':
                return 's';
            default:
                return 's';
        }
    }
}
?>