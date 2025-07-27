<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/initialize.php');
include_once '../database/config.php';
include_once '../core/post.php';

$database = new DatabaseService();
$db = $database->getConnection();

$post = new Post($db);

// Gọi hàm đọc user
$stmt = $post->readUsers();
$num = $stmt->rowCount();

if ($num > 0) {
    $users_arr = [];
    $users_arr["data"] = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $user_item = [
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "password" => $password
        ];

        array_push($users_arr["data"], $user_item);
    }

    http_response_code(200);
    echo json_encode($users_arr);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No users found."]);
}
