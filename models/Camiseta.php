<?php

require_once 'config/database.php';

class Camiseta {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public static function getAll() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT c.*, GROUP_CONCAT(t.nombre) as tallas 
                  FROM camisetas c 
                  LEFT JOIN camiseta_tallas ct ON c.id = ct.camiseta_id 
                  LEFT JOIN tallas t ON ct.talla_id = t.id 
                  GROUP BY c.id
                  ORDER BY c.id DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getById(int $id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT c.*, GROUP_CONCAT(t.nombre) as tallas 
                  FROM camisetas c 
                  LEFT JOIN camiseta_tallas ct ON c.id = ct.camiseta_id 
                  LEFT JOIN tallas t ON ct.talla_id = t.id 
                  WHERE c.id = ? 
                  GROUP BY c.id";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function create(array $data): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        try {
            $conn->beginTransaction();
            
            $query = "INSERT INTO camisetas (titulo, club, pais, tipo, color, precio, precio_oferta, detalles, codigo_producto) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($query);
            $resultado = $stmt->execute([
                $data['titulo'],
                $data['club'],
                $data['pais'],
                $data['tipo'],
                $data['color'],
                $data['precio'],
                $data['precio_oferta'] ?? null,
                $data['detalles'],
                $data['codigo_producto']
            ]);
            
            $camiseta_id = $conn->lastInsertId();
            if (!empty($data['tallas']) && is_array($data['tallas'])) {
                $query_talla = "INSERT INTO camiseta_tallas (camiseta_id, talla_id) VALUES (?, ?)";
                $stmt_talla = $conn->prepare($query_talla);
                
                foreach ($data['tallas'] as $talla_id) {
                    $stmt_talla->execute([$camiseta_id, $talla_id]);
                }
            }
            
            $conn->commit();
            return true;
            
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
    
    public static function update(int $id, array $data): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        try {
            $conn->beginTransaction();
            
            $query = "UPDATE camisetas SET 
                      titulo = ?, club = ?, pais = ?, tipo = ?, color = ?, 
                      precio = ?, precio_oferta = ?, detalles = ?, codigo_producto = ?
                      WHERE id = ?";
            
            $stmt = $conn->prepare($query);
            $resultado = $stmt->execute([
                $data['titulo'],
                $data['club'],
                $data['pais'],
                $data['tipo'],
                $data['color'],
                $data['precio'],
                $data['precio_oferta'] ?? null,
                $data['detalles'],
                $data['codigo_producto'],
                $id
            ]);
            if (isset($data['tallas'])) {
                $delete_tallas = "DELETE FROM camiseta_tallas WHERE camiseta_id = ?";
                $stmt_delete = $conn->prepare($delete_tallas);
                $stmt_delete->execute([$id]);
                if (is_array($data['tallas'])) {
                    $query_talla = "INSERT INTO camiseta_tallas (camiseta_id, talla_id) VALUES (?, ?)";
                    $stmt_talla = $conn->prepare($query_talla);
                    
                    foreach ($data['tallas'] as $talla_id) {
                        $stmt_talla->execute([$id, $talla_id]);
                    }
                }
            }
            
            $conn->commit();
            return true;
            
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
    
    public static function delete(int $id): bool {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "DELETE FROM camisetas WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        return $stmt->execute([$id]);
    }
    
    public static function getPrecioFinal(int $camiseta_id, int $cliente_id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT c.precio, c.precio_oferta, cl.categoria, cl.porcentaje_oferta
                  FROM camisetas c, clientes cl
                  WHERE c.id = ? AND cl.id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$camiseta_id, $cliente_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$resultado) return null;
        
        $precio_base = $resultado['precio'];
        $precio_oferta = $resultado['precio_oferta'];
        $categoria = $resultado['categoria'];
        $porcentaje_descuento = $resultado['porcentaje_oferta'];
        if ($categoria === 'Preferencial' && $precio_oferta !== null) {
            $precio_final = $precio_oferta;
        } else {
            $precio_final = $precio_base;
        }
        
        if ($porcentaje_descuento > 0) {
            $precio_final = $precio_final * (1 - $porcentaje_descuento / 100);
        }
        
        return round($precio_final, 2);
    }
    
    public static function getByCliente(int $cliente_id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT c.*, GROUP_CONCAT(t.nombre) as tallas
                  FROM camisetas c 
                  LEFT JOIN camiseta_tallas ct ON c.id = ct.camiseta_id 
                  LEFT JOIN tallas t ON ct.talla_id = t.id 
                  GROUP BY c.id
                  ORDER BY c.id DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $camisetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($camisetas as &$camiseta) {
            $camiseta['tallas'] = $camiseta['tallas'] ? explode(',', $camiseta['tallas']) : [];
            $camiseta['precio_final'] = self::getPrecioFinal($camiseta['id'], $cliente_id);
        }
        
        return $camisetas;
    }
}
?> 