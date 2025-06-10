<?php

require_once 'config/database.php';

class Talla {
    
    public static function getAll() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM tallas ORDER BY orden ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getById(int $id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM tallas WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function create(array $data): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "INSERT INTO tallas (nombre, orden) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([
            $data['nombre'],
            $data['orden'] ?? 0
        ]);
    }
    
    public static function update(int $id, array $data): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "UPDATE tallas SET nombre = ?, orden = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([
            $data['nombre'],
            $data['orden'] ?? 0,
            $id
        ]);
    }
    
    public static function delete(int $id): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "DELETE FROM tallas WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([$id]);
    }
    
    public static function getByNombre(string $nombre) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM tallas WHERE nombre = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$nombre]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function getByCamiseta(int $camiseta_id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT t.* FROM tallas t 
                  INNER JOIN camiseta_tallas ct ON t.id = ct.talla_id 
                  WHERE ct.camiseta_id = ? 
                  ORDER BY t.orden ASC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$camiseta_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 