<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "backend_examen";
    private $connection;

    public function getConnection() {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("❌ Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}

$database = new Database();
$conn = $database->getConnection();

?> 