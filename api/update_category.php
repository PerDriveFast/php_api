<?php
//header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization');

//include core files
include_once('../core/initialize.php');
//instantiate Post object
$post = new Post($db);

//get raw posted data
$data = json_decode(file_get_contents("php://input"));
//set post properties
$post->category_id = $data->category_id;
$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->id = $data->id;
//update post
if ($post->update()) {
    echo json_encode(
        array('message' => 'Post Updated')
    );
} else {
    echo json_encode(
        array('message' => 'Post Not Updated')
    );
}
