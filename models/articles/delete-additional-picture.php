<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['idP'])){
        try{
            $pictureId = $_POST['idP'];
            $articleId = $_POST['idA'];
            $picturePath = getPathOfOneAdditionPicture($pictureId);
            $delete = deleteAdditionalPicture($pictureId);
            if($delete)
            {
                unlink("../../assets/images/".$picturePath->Path);
                $article = singleArticleById($articleId);
                $pictures = allPictureByArticleId($articleId);
                if(count($pictures) != 0){
                    $article->pictures = $pictures;
                }
                // var_dump($article);
                echo json_encode($article);
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

