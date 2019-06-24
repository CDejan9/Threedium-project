<?php
    function allArticles(){
        global $conn;
        $articles = $conn->query("SELECT * FROM article a INNER JOIN user u ON u.UserId=a.UserId")->fetchAll();
        return $articles;
    }
    function allArticlesByUser($id){
        global $conn;
        $query = "SELECT * FROM article a INNER JOIN user u ON u.UserId=a.UserId WHERE a.UserId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetchAll();
        return $result;
    }
    function singleArticleById($id){
        global $conn;
        $query = "SELECT * FROM article a INNER JOIN user u ON u.UserId=a.UserId WHERE a.ArticleId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetch();
        return $result;
    }
    function allPictureByArticleId($id){
        global $conn;
        $query = "SELECT * FROM article a INNER JOIN picture p ON a.ArticleId = p.ArticleId WHERE a.ArticleId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetchAll();
        return $result;
    }
    function insertArticle($name, $text, $path, $userId){
        global $conn;
        $query = "INSERT INTO article(ArticleName, Text, InitialPicture, UserId) VALUES (:name, :text, :path, :id)";
        $insert = $conn->prepare($query);

        $insert->bindParam(":name", $name);
        $insert->bindParam(":text", $text);
        $insert->bindParam(":path", $path);
        $insert->bindParam(":id", $userId);
        $result = $insert->execute();
        return $result;
    }
    function getLastId(){
        global $conn;
        return $conn->lastInsertId();
    }
    function insertPictures($alt, $path, $id){
        global $conn;
        $query = "INSERT INTO picture VALUES (NULL, :alt, :path, :id)";
        $insert = $conn->prepare($query);

        $insert->bindParam(":alt", $alt);
        $insert->bindParam(":path", $path);
        $insert->bindParam(":id", $id);
        $result = $insert->execute();
        return $result;
    }
    function deleteArticle($id){
        global $conn;
        $query = "DELETE FROM `article` WHERE ArticleId = :id";
        $delete = $conn->prepare($query);

        $delete->bindParam(":id",$id);
        $result = $delete->execute();
        return $result;
    }
?>