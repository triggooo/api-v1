<?php
header('Content-Type: application/json; charset=UTF-8');
include "db.php";
include "crud.php";

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$basePath = "/api/v1/";
$path = str_replace($basePath, "", parse_url($uri, PHP_URL_PATH));
$segments = explode("/", trim($path, "/"));
$request = $segments[0] ?? null;
$id = $segments[1] ?? null;

if ($request !== "notebook") {
    http_response_code(404);
    echo json_encode(["message" => "Endpoint не найден"], JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($method) {
    case 'GET':
        if ($id) {
            getNotebookById($conn, $id);
        } else {
            getNotebooks($conn);
        }
        break;
    case 'POST':
        if ($id) {
            updateNotebook($conn, $id); // я бы написал апдейт через put, но в тестовом задании архитектура требует запрос POST с айди
        } else {
            createNotebook($conn);
        }
        break;
    case 'DELETE':
        if ($id) {
            deleteNotebook($conn, $id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Для удаления необходимо Id"], JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Метод не поддерживается"], JSON_UNESCAPED_UNICODE);
        break;
}
?>
