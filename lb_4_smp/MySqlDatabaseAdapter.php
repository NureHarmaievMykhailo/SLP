<?php
require_once 'databaseAdapter.php';
require_once 'config.php';

class MySqlDatabaseAdapter implements DatabaseAdapter {
    private $pdo;

    public function connect() {
        $dsn = "mysql:host=" . __HOSTNAME__ . ";dbname=" . __DATABASE__ . ";charset=utf8mb4";
        $this->pdo = new PDO($dsn, __USERNAME__, __PASSWORD__, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function query($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}
?>