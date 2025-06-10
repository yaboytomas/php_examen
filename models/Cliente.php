<?php

require_once 'config/database.php';

class Cliente {
    
    public static function getAll() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM clientes ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getById(int $id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function create(array $data): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "INSERT INTO clientes (nombre_comercial, rut, direccion, categoria, contacto_nombre, contacto_email, porcentaje_oferta) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([
            $data['nombre_comercial'],
            $data['rut'],
            $data['direccion'],
            $data['categoria'],
            $data['contacto_nombre'],
            $data['contacto_email'],
            $data['porcentaje_oferta'] ?? 0.00
        ]);
    }
    
    public static function update(int $id, array $data): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "UPDATE clientes SET 
                  nombre_comercial = ?, rut = ?, direccion = ?, categoria = ?, 
                  contacto_nombre = ?, contacto_email = ?, porcentaje_oferta = ?
                  WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([
            $data['nombre_comercial'],
            $data['rut'],
            $data['direccion'],
            $data['categoria'],
            $data['contacto_nombre'],
            $data['contacto_email'],
            $data['porcentaje_oferta'] ?? 0.00,
            $id
        ]);
    }
    
    public static function delete(int $id): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "DELETE FROM clientes WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([$id]);
    }
    
    public static function hasAssociatedPriceCalculations(int $id): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT COUNT(*) as count FROM (
                    SELECT 1 FROM camisetas c, clientes cl 
                    WHERE cl.id = ? AND c.id IS NOT NULL
                    LIMIT 1
                  ) as check_association";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] > 0;
    }
    
    public static function getByRut(string $rut) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM clientes WHERE rut = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$rut]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function getByCategoria(string $categoria) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM clientes WHERE categoria = ? ORDER BY nombre_comercial";
        $stmt = $conn->prepare($query);
        $stmt->execute([$categoria]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 