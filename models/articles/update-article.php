<?php
session_start();
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try{
        $articleName = $_POST['articleName'];
        $articleText = $_POST['articleText'];
        $articleId = $_POST['articleId'];
       
        $locationInitialPicture = "";
        $modifiedDate = date("Y-m-d h:i:s");

        $initialPicturePath = getPathOfInitialPicture($articleId);
        if(isset($_FILES['fImage'])){
            $initialPicture = $_FILES['fImage'];
            if($initialPicturePath->InitialPicture == $initialPicture['name']){
                $locationInitialPicture = $initialPicture['name'];
            }
            else{
                $locationInitialPicture = time()."_".$initialPicture['name'];
            }
            if(move_uploaded_file($initialPicture['tmp_name'], "../../assets/images/".$locationInitialPicture)){
                $updated = updateArticle($articleName, $articleText, $locationInitialPicture, $articleId, $modifiedDate); 
                if($updated){
                    include "add-additional-pictures.php";
                }
            }
        }
        else{
            $locationInitialPicture = $initialPicturePath->InitialPicture;
            $updated = updateArticle($articleName, $articleText, $locationInitialPicture, $articleId, $modifiedDate);
            if($updated){
                include "add-additional-pictures.php";
            }
        }  
        
        echo json_encode(['message'=>'Updated']);
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

