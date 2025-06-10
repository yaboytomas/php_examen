<?php

require_once 'models/Cliente.php';

class ClienteController {
    
    public static function index() {
        header('Content-Type: application/json');
        
        try {
            $clientes = Cliente::getAll();
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Clientes obtenidos correctamente',
                'data' => $clientes,
                'total' => count($clientes)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener clientes: ' . $e->getMessage()
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
            $cliente = Cliente::getById((int)$id);
            
            if (!$cliente) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ]);
                return;
            }
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Cliente encontrado',
                'data' => $cliente
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener cliente: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function store() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['nombre_comercial', 'rut'];
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
        
        if (isset($input['categoria']) && !in_array($input['categoria'], ['Regular', 'Preferencial'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'La categoría debe ser "Regular" o "Preferencial"'
            ]);
            return;
        }
        
        if (isset($input['porcentaje_oferta']) && (!is_numeric($input['porcentaje_oferta']) || $input['porcentaje_oferta'] < 0 || $input['porcentaje_oferta'] > 100)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'El porcentaje de oferta debe ser un número entre 0 y 100'
            ]);
            return;
        }
        
        try {
            $cliente_existente = Cliente::getByRut($input['rut']);
            if ($cliente_existente) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya existe un cliente con ese RUT'
                ]);
                return;
            }
            
            $resultado = Cliente::create($input);
            
            if ($resultado) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Cliente creado exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear el cliente'
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
        
        $cliente_existente = Cliente::getById((int)$id);
        if (!$cliente_existente) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ]);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required_fields = ['nombre_comercial', 'rut'];
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
        
        if (isset($input['categoria']) && !in_array($input['categoria'], ['Regular', 'Preferencial'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'La categoría debe ser "Regular" o "Preferencial"'
            ]);
            return;
        }
        
        if (isset($input['porcentaje_oferta']) && (!is_numeric($input['porcentaje_oferta']) || $input['porcentaje_oferta'] < 0 || $input['porcentaje_oferta'] > 100)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'El porcentaje de oferta debe ser un número entre 0 y 100'
            ]);
            return;
        }
        
        try {
            $otro_cliente = Cliente::getByRut($input['rut']);
            if ($otro_cliente && $otro_cliente['id'] != $id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya existe otro cliente con ese RUT'
                ]);
                return;
            }
            
            $resultado = Cliente::update((int)$id, $input);
            
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Cliente actualizado exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el cliente'
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
        
        $cliente_existente = Cliente::getById((int)$id);
        if (!$cliente_existente) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ]);
            return;
        }
        
        if (Cliente::hasAssociatedPriceCalculations((int)$id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No se puede eliminar el cliente porque tiene camisetas asociadas para cálculo de precios'
            ]);
            return;
        }
        
        try {
            $resultado = Cliente::delete((int)$id);
            
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Cliente eliminado exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el cliente'
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
    
    public static function getByCategoria($categoria) {
        header('Content-Type: application/json');
        
        if (!in_array($categoria, ['Regular', 'Preferencial'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Categoría debe ser "Regular" o "Preferencial"'
            ]);
            return;
        }
        
        try {
            $clientes = Cliente::getByCategoria($categoria);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => "Clientes de categoría '$categoria' obtenidos correctamente",
                'data' => $clientes,
                'total' => count($clientes)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener clientes: ' . $e->getMessage()
            ]);
        }
    }
}
?> 