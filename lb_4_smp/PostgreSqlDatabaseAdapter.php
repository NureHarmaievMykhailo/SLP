<?php
require_once  __DIR__ . '/../databaseAdapter.php';

class PostgreSqlDatabaseAdapter implements DatabaseAdapter {
    private $pdo;

    public function connect() {
        $dsn = "pgsql:host=localhost;port=5433;dbname=fy_db;user=admin;password=1234";
        $this->pdo = new PDO($dsn);
    }

    public function query($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function close() {
        $this->pdo = null;
    }
}
?>