<?php
session_start();
header("Content-Type: application/json");

if(isset($_GET['limit'])){
    require_once "../../config/connection.php";
    include "functions.php";

    $limit = $_GET['limit'];
    $articles = allArticles($limit);
    $num_of_articles = get_pagination_count_all();

    echo json_encode([
        "articles" => $articles,
        "num_of_articles" => $num_of_articles
    ]);
} else {
    echo json_encode(["message"=> "Limit not passed."]);
    http_response_code(400); // Bad request
}