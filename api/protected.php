<?php
include_once('../core/initialize.php'); // Đường dẫn đúng
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$secret_key = "YOUR_SECRET_KEY";
$jwt = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(400);
    echo json_encode(["message" => "Authorization header missing."]);
    exit;
}

$arr = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
$jwt = $arr[1] ?? null;

if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($secret_key, 'HS256'));

        echo json_encode([
            "message" => "Access granted.",
            "data" => $decoded
        ]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode([
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "JWT not found."]);
}
