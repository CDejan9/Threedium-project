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
?>