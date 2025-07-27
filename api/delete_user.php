<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Kết nối DB
include_once '../database/config.php';
include_once '../core/initialize.php';

$database = new DatabaseService();
$db = $database->getConnection();

$user = new Post($db);

// Lấy dữ liệu JSON gửi lên
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $user->id = $data->id;

    if ($user->deleteUser()) {
        http_response_code(200);
        echo json_encode(["message" => "User was deleted."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to delete user."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "User ID is required."]);
}
