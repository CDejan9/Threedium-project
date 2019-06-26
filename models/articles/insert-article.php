<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try{
        $articleName = $_POST['articleName'];
        $articleText = $_POST['articleText'];
        $initialPicture = $_FILES['fImage'];
        
        $location = "../../assets/images/".time()."_".$initialPicture['name'];

        if(move_uploaded_file($initialPicture['tmp_name'], $location)){
            $inserted = insertArticle($articleName, $articleText,time()."_".$initialPicture['name'], $_SESSION['user']->UserId);
            if($inserted){
                $articleId = getLastId();
                include "add-additional-pictures.php";
            }
            $articles = allArticlesByUser( $_SESSION['user']->UserId);
            $num_of_articles = get_pagination_count($_SESSION['user']->UserId);
       
            echo json_encode([
                "articles" => $articles,
                "num_of_articles" => $num_of_articles
            ]);
            http_response_code(200);
        }
    }
    catch (PDOException $exception)
    {
        http_response_code(500);
    }
}
else{
    http_response_code(404);
}

