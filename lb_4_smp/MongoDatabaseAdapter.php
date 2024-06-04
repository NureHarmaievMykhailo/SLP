<?php
require_once 'DatabaseAdapter.php';
require_once 'vendor/autoload.php';

use MongoDB\Client;

class MongoDatabaseAdapter implements DatabaseAdapter {
    private $client;
    private $db;

    public function connect() {
        $this->client = new Client("mongodb://localhost:27017");
        $this->db = $this->client->selectDatabase(__DATABASE__);
    }

    public function query($sql, $params = []) {
        // Реалізуйте метод query відповідно до вашої логіки для MongoDB
    }
}
?>
