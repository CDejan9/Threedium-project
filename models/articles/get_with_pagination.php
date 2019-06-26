<?php
session_start();
header("Content-Type: application/json");

if(isset($_GET['limit'])){
    require_once "../../config/connection.php";
    include "functions.php";

    $limit = $_GET['limit'];
    $articles = allArticlesByUser($_SESSION['user']->UserId, $limit);
    $num_of_articles = get_pagination_count($_SESSION['user']->UserId);

    echo json_encode([
        "articles" => $articles,
        "num_of_articles" => $num_of_articles
    ]);
    http_response_code(200); 
} else {
    echo json_encode(["message"=> "Limit not passed."]);
    http_response_code(400); 
}