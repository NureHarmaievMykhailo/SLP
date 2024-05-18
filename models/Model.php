<?php
$root = __DIR__ . "/..";
require_once("$root/config.php");

abstract class Model {
    protected $table;

    public function getTable() {
        return $this->table;
    }

    protected function getById($id, $db = __DATABASE__) {
        // Assure that id is an integer
        $id = intval($id);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE $db;");
        $result = mysqli_query($conn, "SELECT * FROM $this->table WHERE id = $id");
        mysqli_close($conn); 
        return $result;
    }

    /**
     * Insert a row in the database.
     *
     * @param array $data Associative array of column names and values to insert.
     * @param $db Database to be used. Defaults to config value.
     * @return bool Returns true on success, false on failure.
     */
    protected function insert(array $data, string $db = __DATABASE__) {
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, $db);

        if ($mysqli->connect_error) {
            return false;
        }

        $columns = [];
        $types = '';
        $values = [];
        $placeholders = [];
        foreach ($data as $column => $value) {
            $columns[] = $column;
            $placeholders[] = '?';
            $types .= $this->getType($value);
            $values[] = $value;
        }
        $columns = implode(", ", $columns);
        $placeholders = implode(", ", $placeholders);

        // Combine into the full SQL statement
        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders);";

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
     * Update a row in the database.
     *
     * @param int $id Id of the element to be updated.
     * @param array $data Associative array of column names and values to update.
     * @param $db Database to be used. Defaults to config value.
     * @return bool Returns true on success, false on failure.
     */
    protected function update(int $id, array $data, string $db = __DATABASE__) {
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
     * @param int $id Id of the element to be updated.
     * @param $db Database to be used. Defaults to config value.
     * @return bool Returns true on success, false on failure.
     */
    protected function delete(int $id, string $db = __DATABASE__) {
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

    protected function getAll($db, $table, $limit) {
        // Assure that limit is an integer
        $limit = intval($limit);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE $db;");
        $result = mysqli_query($conn, "SELECT * FROM $table LIMIT = $limit");
        mysqli_close($conn); 
        return $result;
    }

    protected function executeSQL($db, $query) {
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE $db;");
        $result = mysqli_query($conn, $query);
        mysqli_close($conn); 
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