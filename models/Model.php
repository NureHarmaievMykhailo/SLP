<?php
$root = __DIR__ . "/..";
require_once("$root/config.php");

abstract class Model {
    protected function getById($db, $id, $table) {
        // Assure that id is an integer
        $id = intval($id);
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE $db;");
        $result = mysqli_query($conn, "SELECT * FROM $table WHERE id = $id");
        mysqli_close($conn); 
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
    // Create methods to update, delete 
    protected function executeSQL($db, $query) {
        $conn = mysqli_connect(__HOSTNAME__, __USERNAME__, __PASSWORD__);
        mysqli_query($conn, "USE $db;");
        $result = mysqli_query($conn, $query);
        mysqli_close($conn); 
        return $result;
    }
}
?>