<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['id'])){
        try{
            $articleId = $_POST['id'];
            $delete = deleteArticle($articleId);
            if($delete)
            {
                $articles = allArticlesByUser($_SESSION['user']->UserId);
                echo json_encode($articles);
                http_response_code(200);
            }
        }
        catch (PDOException $exception)
        {
            http_response_code(500);
        }
    }
}
else{
    http_response_code(404);
}

