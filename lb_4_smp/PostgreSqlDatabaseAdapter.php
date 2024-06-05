<?php
require_once 'databaseAdapter.php';
require_once __DIR__ . '/../config.php';

class PostgreSqlDatabaseAdapter {
    private $connection;

    public function connect() {

        $dsn = "pgsql:host=". __HOST__ .";port=".__PORT__.";dbname=".__DBNAME__."";
        try {
            $this->connection = new PDO($dsn, __USER__, __PWD__, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo 'Query error: ' . $e->getMessage();
            exit;
        }
    }

    public function close() {
        $this->connection = null;
    }
}
?>