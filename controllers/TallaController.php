git<?php

require_once 'models/Talla.php';

class TallaController {
    
    public static function index() {
        header('Content-Type: application/json');
        
        try {
            $tallas = Talla::getAll();
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Tallas obtenidas correctamente',
                'data' => $tallas,
                'total' => count($tallas)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener tallas: ' . $e->getMessage()
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
            $talla = Talla::getById((int)$id);
            
            if (!$talla) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Talla no encontrada'
                ]);
                return;
            }
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Talla encontrada',
                'data' => $talla
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener talla: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function store() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['nombre'];
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
        
        if (isset($input['orden']) && !is_numeric($input['orden'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'El orden debe ser un número'
            ]);
            return;
        }
        
        try {
            $talla_existente = Talla::getByNombre($input['nombre']);
            if ($talla_existente) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya existe una talla con ese nombre'
                ]);
                return;
            }
            
            $resultado = Talla::create($input);
            
            if ($resultado) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Talla creada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear la talla'
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
        
        $talla_existente = Talla::getById((int)$id);
        if (!$talla_existente) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Talla no encontrada'
            ]);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['nombre'];
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
        
        if (isset($input['orden']) && !is_numeric($input['orden'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'El orden debe ser un número'
            ]);
            return;
        }
        
        try {
            $otra_talla = Talla::getByNombre($input['nombre']);
            if ($otra_talla && $otra_talla['id'] != $id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya existe otra talla con ese nombre'
                ]);
                return;
            }
            
            $resultado = Talla::update((int)$id, $input);
            
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Talla actualizada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar la talla'
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
        
        $talla_existente = Talla::getById((int)$id);
        if (!$talla_existente) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Talla no encontrada'
            ]);
            return;
        }
        
        try {
            $resultado = Talla::delete((int)$id);
            
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Talla eliminada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar la talla'
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
}
?> 