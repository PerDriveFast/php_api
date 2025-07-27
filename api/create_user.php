<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Kết nối cơ sở dữ liệu (CẦN THÊM FILE config)
include_once '../database/config.php'; // Đảm bảo đúng đường dẫn

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

// Nhận dữ liệu JSON
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra dữ liệu đầu vào
if (
    empty($data->id) ||
    empty($data->first_name) ||
    empty($data->last_name) ||
    empty($data->email) ||
    empty($data->password)
) {
    echo json_encode(["message" => "Incomplete data."]);
    exit;
}

// Làm sạch dữ liệu
$id = htmlspecialchars(strip_tags($data->id));
$first_name = htmlspecialchars(strip_tags($data->first_name));
$last_name = htmlspecialchars(strip_tags($data->last_name));
$email = htmlspecialchars(strip_tags($data->email));
$password = password_hash($data->password, PASSWORD_BCRYPT);

// Kiểm tra email trùng
$checkQuery = "SELECT id FROM users WHERE email = :email";
$stmt = $conn->prepare($checkQuery);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo json_encode(["message" => "Email already registered."]);
    exit;
}

// Thêm người dùng mới
$insertQuery = "INSERT INTO users (id, first_name, last_name, email, password)
                VALUES (:id, :first_name, :last_name, :email, :password)";
$stmt = $conn->prepare($insertQuery);

$stmt->bindParam(':id', $id);
$stmt->bindParam(':first_name', $first_name);
$stmt->bindParam(':last_name', $last_name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);

if ($stmt->execute()) {
    echo json_encode(["message" => "User registered successfully."]);
} else {
    echo json_encode(["message" => "Registration failed."]);
}
