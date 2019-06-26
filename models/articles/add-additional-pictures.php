<?php
if(isset($_POST['numberOfNewFiles'])){
    $numberOfNew = $_POST['numberOfNewFiles'];
    if($numberOfNew != 0){
        for($i=0; $i<$numberOfNew; $i++){
            $imgName = $_FILES['file'.($i)]['name'];
            $imgTmpName = $_FILES['file'.($i)]['tmp_name'];
            $path = "../../assets/images/".time()."_".$imgName;
            if(move_uploaded_file($imgTmpName, $path)){
                $insertedAdditional = insertPictures($articleName, time()."_".$imgName, $articleId);
            }
        }
    }
}

