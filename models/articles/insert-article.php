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
                $numberOfAdditionalPictures = count($_FILES);
                if($numberOfAdditionalPictures > 1){
                    $lastId = getLastId();
                    for($i=1; $i<count($_FILES); $i++){
                        $imgName = $_FILES['file'.($i-1)]['name'];
                        $imgTmpName = $_FILES['file'.($i-1)]['tmp_name'];
                        $path = "../../assets/images/".time()."_".$imgName;
                        if(move_uploaded_file($imgTmpName, $path)){
                            $insertedAdditional = insertPictures($articleName, time()."_".$imgName, $lastId);
                            if($insertedAdditional){
                                $articles = allArticlesByUser( $_SESSION['user']->UserId);
                                echo json_encode($articles);
                                http_response_code(200);
                            }
                        }
                    }
                }
                else{
                    $articles = allArticlesByUser( $_SESSION['user']->UserId);
                    echo json_encode($articles);
                    http_response_code(200);
                }
            } 
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

