<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization');

// Include core files
include_once('../core/initialize.php');

// Instantiate Post
$post = new Post($db);

// Get raw data from input (JSON)
$data = json_decode(file_get_contents("php://input"));

// Ưu tiên lấy ID từ body, nếu không có thì lấy từ query string
$post->id = $data->id ?? $_GET['id'] ?? null;

// Check if ID is set
if (!$post->id) {
    echo json_encode(['message' => 'Post ID not provided']);
    exit();
}

// Delete post
if ($post->delete()) {
    echo json_encode(['message' => 'Post Deleted']);
} else {
    echo json_encode(['message' => 'Post Not Deleted']);
}
