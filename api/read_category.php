<?php
//header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once '../database/config.php';
include_once '../core/post.php';

$dbService = new DatabaseService();
$conn = $dbService->getConnection();

$post = new Post($conn);

//post query
$result = $post->read();
//get row count
$num = $result->rowCount();
//check if any posts
if ($num > 0) {
    //post array
    $posts_arr = array();
    $posts_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $post_item = array(
            'id' => $id,
            'category_id' => $category_id,
            'category_name' => $category_name,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'create_at' => $create_at
        );
        //push to "data"
        array_push($posts_arr['data'], $post_item);
    }
    //convert to JSON and output
    echo json_encode($posts_arr);
} else {
    //no posts found
    echo json_encode(
        array('message' => 'No Posts Found')
    );
}

//http://localhost:8000/api/read.php
