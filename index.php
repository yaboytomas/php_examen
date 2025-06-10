<?php

header("Content-Type: application/json");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'controllers/CamisetaController.php';
require_once 'controllers/ClienteController.php';
require_once 'controllers/TallaController.php';

$method = $_SERVER["REQUEST_METHOD"];
$url = $_SERVER['REQUEST_URI'];
$basePath = '/backend_examen/';
$url = str_replace($basePath, '', $url);

$path = parse_url($url, PHP_URL_PATH);
$urlParts = explode('/', trim($path, 'cl/'));
if (empty($urlParts[0]) || $urlParts[0] === '') {
    echo json_encode([
        'success' => true,
        'message' => '🏆 Bienvenido a la API de TodoCamisetas',
        'version' => '1.0.0',
        'endpoints' => [
            'camisetas' => '/api/camisetas',
            'clientes' => '/api/clientes',
            'tallas' => '/api/tallas'
        ],
        'documentacion' => 'Ver docs/ para más información'
    ]);
    exit();
}

if ($urlParts[0] !== 'api') {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint no encontrado',
        'method' => $method,
        'path' => $path
    ]);
    exit();
}

$resource = isset($urlParts[1]) ? $urlParts[1] : '';

switch ($resource) {
    case "camisetas":
        handleCamisetaRequests($method, $urlParts);
        break;
    
    case "clientes":
        handleClienteRequests($method, $urlParts);
        break;
    
    case "tallas":
        handleTallaRequests($method, $urlParts);
        break;
        
    default:
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint no encontrado',
            'method' => $method,
            'path' => $path
        ]);
        break;
}

function handleCamisetaRequests($method, $urlParts) {
    switch ($method) {
        case "GET":
            if (count($urlParts) == 2) {
                CamisetaController::index();
            }
            elseif (count($urlParts) == 4 && is_numeric($urlParts[2]) && $urlParts[3] === 'precio') {
                CamisetaController::getPrecioFinal($urlParts[2]);
            }
            elseif (count($urlParts) == 4 && $urlParts[2] === 'cliente' && is_numeric($urlParts[3])) {
                CamisetaController::getByCliente($urlParts[3]);
            }
            elseif (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                CamisetaController::show($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "POST":
            if (count($urlParts) == 2) {
                CamisetaController::store();
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "PUT":
            if (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                CamisetaController::update($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "DELETE":
            if (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                CamisetaController::destroy($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Método no permitido"]);
            break;
    }
}

function handleClienteRequests($method, $urlParts) {
    switch ($method) {
        case "GET":
            if (count($urlParts) == 2) {
                ClienteController::index();
            }
            elseif (count($urlParts) == 4 && $urlParts[2] === 'categoria' && in_array($urlParts[3], ['Regular', 'Preferencial'])) {
                ClienteController::getByCategoria($urlParts[3]);
            }
            elseif (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                ClienteController::show($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "POST":
            if (count($urlParts) == 2) {
                ClienteController::store();
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "PUT":
            if (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                ClienteController::update($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "DELETE":
            if (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                ClienteController::destroy($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Método no permitido"]);
            break;
    }
}

function handleTallaRequests($method, $urlParts) {
    switch ($method) {
        case "GET":
            if (count($urlParts) == 2) {
                TallaController::index();
            }
            elseif (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                TallaController::show($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "POST":
            if (count($urlParts) == 2) {
                TallaController::store();
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "PUT":
            if (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                TallaController::update($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        case "DELETE":
            if (count($urlParts) == 3 && is_numeric($urlParts[2])) {
                TallaController::destroy($urlParts[2]);
            }
            else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Método no permitido"]);
            break;
    }
}

?> 