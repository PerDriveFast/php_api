<?php

//header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

//include core files
include_once('../core/initialize.php');
//instantiate Post object
$post = new Post($db);


$post->id = isset($_GET['id']) ? $_GET['id'] : die();
$post->read_single();

$post_array = array(
    'id' => $post->id,
    'category_id' => $post->category_id,
    'category_name' => $post->category_name,
    'title' => $post->title,
    'body' => html_entity_decode($post->body),
    'author' => $post->author,
    'create_at' => $post->create_at
);

//make a JSON
print_r(json_encode($post_array));

 // http://localhost:8000/api/read_single.php?id=2