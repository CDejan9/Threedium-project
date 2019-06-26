<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if(isset($_POST['id'])){
    try{
        $articles = allArticlesByUser($_POST['id']);
        $num_of_articles = get_pagination_count($_POST['id']);
        // var_dump($num_of_articles);
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
}
else{
    http_response_code(404);
}
