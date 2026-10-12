<?php
require_once __DIR__ . '/config.php';

class Database {
    protected $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            error_log($this->conn->connect_error);
            die('Connection failed.');
        }
    }
}
?>