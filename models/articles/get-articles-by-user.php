<?php
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if(isset($_POST['id'])){
    try{
        $articles = allArticlesByUser($_POST['id']);
        echo json_encode($articles);
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
