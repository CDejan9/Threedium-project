<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['id'])){
        try{
            $articleId = $_POST['id'];
            $conn->beginTransaction();

            //brisanje dodatnh slika iz foldera
            $picturesPaths = getAdditionalPicturesPaths($articleId);
            foreach($picturesPaths as $path){
                unlink("../../assets/images/".$path->Path);
            }

            //brisanje inicijalne slike iz foldera
            $initialImagePath = getPathOfInitialPicture($articleId);
            // var_dump($initialImagePath);
            if($initialImagePath){
                unlink("../../assets/images/".$initialImagePath->InitialPicture);
            }
        
            //brisanje svih artikla i njegovih dodatnih slika iz baze
            $delete = deleteArticle($articleId);
            
            //dohvatanje svih artikala za ulogovanog korisnika
            $articles = allArticlesByUser($_SESSION['user']->UserId);
            $num_of_articles = get_pagination_count($_SESSION['user']->UserId);
       
        
            $conn->commit();
            echo json_encode([
                "articles" => $articles,
                "num_of_articles" => $num_of_articles
            ]);
            http_response_code(200);
            
        }
        catch (PDOException $exception){
            $conn->rollback();
            echo json_encode(['message'=> $ex->getMessage()]);
            http_response_code(500);
        }
    }
}
else{
    http_response_code(404);
}

