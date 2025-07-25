<?php

define('APP_NAME', 'PHP REST API TUTORIAL');

class DatabaseService
{
    private $host = 'localhost';
    private $db_name = 'php_api';
    private $username = 'root';
    private $password = 'thanhtrung123@#Z';
    private $conn;

    public function getConnection()
    {
        if ($this->conn) return $this->conn;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        } catch (PDOException $e) {
            echo json_encode([
                "message" => "Database connection error",
                "error" => $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
