<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Autoload Composer packages (JWT, v.v.)
require_once __DIR__ . '/../vendor/autoload.php';

// Include dependencies
include_once('../core/initialize.php');
include_once '../database/config.php';
include_once '../core/post.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Lấy dữ liệu JSON từ client
$data = json_decode(file_get_contents("php://input"));

// Lấy headers
$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["message" => "Authorization header missing."]);
    exit;
}

// Lấy JWT từ header (dạng Bearer token)
$authHeader = $headers['Authorization'];
$arr = explode(" ", $authHeader);

if (count($arr) !== 2 || $arr[0] !== "Bearer") {
    http_response_code(401);
    echo json_encode(["message" => "Access denied.", "error" => "Wrong token format"]);
    exit;
}

$jwt = $arr[1];

// Giải mã JWT
$key = "YOUR_SECRET_KEY"; // Thay bằng khóa bí mật dùng để tạo JWT

try {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Access denied.", "error" => $e->getMessage()]);
    exit;
}

// Kiểm tra dữ liệu đầu vào
if (
    !isset($data->id) ||
    !isset($data->first_name) ||
    !isset($data->last_name) ||
    !isset($data->email) ||
    !isset($data->password)
) {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
    exit;
}

// Kết nối DB
$database = new DatabaseService();
$db = $database->getConnection();

// Khởi tạo đối tượng Post (hoặc User nếu bạn rename lại)
$post = new Post($db);
$post->id = $data->id;
$post->first_name = $data->first_name;
$post->last_name = $data->last_name;
$post->email = $data->email;
$post->password = password_hash($data->password, PASSWORD_BCRYPT);

// Gọi hàm updateUser() (hàm này bạn đã viết trong class Post)
if ($post->updateUser()) {
    http_response_code(200);
    echo json_encode(["message" => "User was updated."]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Unable to update user."]);
}
