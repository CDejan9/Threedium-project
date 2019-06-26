<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";

try{
    $articles = allArticles();
    $num_of_articles = get_pagination_count_all();

    echo json_encode([
        "articles" => $articles,
        "num_of_articles" => $num_of_articles
    ]);
    http_response_code(200);
}
catch (PDOException $exception)
{
    http_response_code(500);
}
