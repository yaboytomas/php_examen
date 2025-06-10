<?php

require_once 'models/Camiseta.php';

class CamisetaController {
    
    public static function index() {
        header('Content-Type: application/json');
        
        try {
            $camisetas = Camiseta::getAll();
            
            foreach ($camisetas as &$camiseta) {
                $camiseta['tallas'] = $camiseta['tallas'] ? explode(',', $camiseta['tallas']) : [];
            }
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Camisetas obtenidas correctamente',
                'data' => $camisetas,
                'total' => count($camisetas)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener camisetas: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function show($id) {
        header('Content-Type: application/json');
        
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID debe ser un número válido'
            ]);
            return;
        }
        
        try {
            $camiseta = Camiseta::getById((int)$id);
            
            if (!$camiseta) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Camiseta no encontrada'
                ]);
                return;
            }
            
            $camiseta['tallas'] = $camiseta['tallas'] ? explode(',', $camiseta['tallas']) : [];
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Camiseta encontrada',
                'data' => $camiseta
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener camiseta: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function store() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['titulo', 'club', 'precio', 'codigo_producto'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => "El campo '$field' es obligatorio"
                ]);
                return;
            }
        }
        
        if (!is_numeric($input['precio'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'El precio debe ser un número válido'
            ]);
            return;
        }
        
        $data = [
            'titulo' => $input['titulo'],
            'club' => $input['club'],
            'pais' => $input['pais'] ?? null,
            'tipo' => $input['tipo'] ?? null,
            'color' => $input['color'] ?? null,
            'precio' => $input['precio'],
            'precio_oferta' => isset($input['precio_oferta']) && is_numeric($input['precio_oferta']) ? $input['precio_oferta'] : null,
            'detalles' => $input['detalles'] ?? null,
            'codigo_producto' => $input['codigo_producto'],
            'tallas' => $input['tallas'] ?? []
        ];
        
        try {
            $resultado = Camiseta::create($data);
            
            if ($resultado) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Camiseta creada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear la camiseta'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function update($id) {
        header('Content-Type: application/json');
        
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID debe ser un número válido'
            ]);
            return;
        }
        
        $camiseta_existente = Camiseta::getById((int)$id);
        if (!$camiseta_existente) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Camiseta no encontrada'
            ]);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['titulo', 'club', 'precio', 'codigo_producto'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => "El campo '$field' es obligatorio"
                ]);
                return;
            }
        }
        
        $data = [
            'titulo' => $input['titulo'],
            'club' => $input['club'],
            'pais' => $input['pais'] ?? null,
            'tipo' => $input['tipo'] ?? null,
            'color' => $input['color'] ?? null,
            'precio' => $input['precio'],
            'precio_oferta' => isset($input['precio_oferta']) && is_numeric($input['precio_oferta']) ? $input['precio_oferta'] : null,
            'detalles' => $input['detalles'] ?? null,
            'codigo_producto' => $input['codigo_producto'],
            'tallas' => $input['tallas'] ?? []
        ];
        
        try {
            $resultado = Camiseta::update((int)$id, $data);
            
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Camiseta actualizada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar la camiseta'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function destroy($id) {
        header('Content-Type: application/json');
        
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID debe ser un número válido'
            ]);
            return;
        }
        
        $camiseta_existente = Camiseta::getById((int)$id);
        if (!$camiseta_existente) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Camiseta no encontrada'
            ]);
            return;
        }
        
        try {
            $resultado = Camiseta::delete((int)$id);
            
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Camiseta eliminada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar la camiseta'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function getPrecioFinal($id) {
        header('Content-Type: application/json');
        
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID de camiseta debe ser un número válido'
            ]);
            return;
        }
        
        $cliente_id = $_GET['cliente_id'] ?? null;
        
        if (!$cliente_id || !is_numeric($cliente_id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID de cliente es obligatorio y debe ser un número válido'
            ]);
            return;
        }
        
        try {
            $precio_final = Camiseta::getPrecioFinal((int)$id, (int)$cliente_id);
            
            if ($precio_final === null) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Camiseta o cliente no encontrado'
                ]);
                return;
            }
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Precio calculado exitosamente',
                'data' => [
                    'camiseta_id' => (int)$id,
                    'cliente_id' => (int)$cliente_id,
                    'precio_final' => $precio_final
                ]
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al calcular precio: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function getByCliente($cliente_id) {
        header('Content-Type: application/json');
        
        if (!is_numeric($cliente_id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID de cliente debe ser un número válido'
            ]);
            return;
        }
        
        try {
            $camisetas = Camiseta::getByCliente((int)$cliente_id);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Camisetas del cliente obtenidas correctamente',
                'data' => $camisetas,
                'total' => count($camisetas),
                'cliente_id' => (int)$cliente_id
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener camisetas del cliente: ' . $e->getMessage()
            ]);
        }
    }
}
?> 