<?php
    define("ARTICLE_OFFSET", 3);
    function allArticles($limit = 0){
        global $conn;
        $query = "SELECT a.*, a.CreatedAt as created, u.* FROM article a INNER JOIN user u ON u.UserId=a.UserId LIMIT :limit, :offset";
        $select = $conn->prepare($query);

        $limit = ((int) $limit) * ARTICLE_OFFSET;
        $select->bindParam(":limit", $limit, PDO::PARAM_INT); 

        $offset = ARTICLE_OFFSET;
        $select->bindParam(":offset", $offset, PDO::PARAM_INT);

        $select->execute(); 

        $articles = $select->fetchAll();
        return $articles;
    }
    function allArticlesByUser($id, $limit = 0){
        global $conn;
        $query = "SELECT a.*, a.CreatedAt as created, u.* FROM article a INNER JOIN user u ON u.UserId=a.UserId WHERE a.UserId = :id LIMIT :limit, :offset";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $limit = ((int) $limit) * ARTICLE_OFFSET;
        $select->bindParam(":limit", $limit, PDO::PARAM_INT); 

        $offset = ARTICLE_OFFSET;
        $select->bindParam(":offset", $offset, PDO::PARAM_INT);
        $select->execute();
        
        $result = $select->fetchAll();
        return $result;
    }
    function singleArticleById($id){
        global $conn;
        $query = "SELECT a.*, a.CreatedAt as created, u.* FROM article a INNER JOIN user u ON u.UserId=a.UserId WHERE a.ArticleId = :id";
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

    function deleteAdditionalPicture($id){
        global $conn;
        $query = "DELETE FROM `picture` WHERE PictureId = :id";
        $delete = $conn->prepare($query);

        $delete->bindParam(":id",$id);
        $result = $delete->execute();
        return $result;
    }

    function getPathOfInitialPicture($id){
        global $conn;
        $query = "SELECT InitialPicture FROM article WHERE ArticleId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetch();
        return $result;
    }

    function getPathOfOneAdditionPicture($id){
        global $conn;
        $query = "SELECT Path FROM picture WHERE PictureId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetch();
        return $result;
    }

    function getAdditionalPicturesPaths($id){
        global $conn;
        $query = "SELECT Path FROM picture WHERE ArticleId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetchAll();
        return $result;
    }

    function updateArticle($articleName, $articleText, $locationInitialPicture, $articleId, $modifiedDate){
        global $conn;
        $query = "UPDATE article SET ArticleName= :name, Text= :text, InitialPicture=:initial, Modified=:modified WHERE ArticleId=:id";
        $update = $conn->prepare($query);

        $update->bindParam(":name", $articleName);
        $update->bindParam(":text", $articleText);
        $update->bindParam(":initial", $locationInitialPicture);
        $update->bindParam(":modified", $modifiedDate);
        $update->bindParam(":id", $articleId);
        $result = $update->execute();
        return $result;
    }

    function updatePictures($articleName, $path, $pictureId){
        global $conn;
        $query = "UPDATE picture SET Alt= :name, Path= :path WHERE PictureId=:id";
        $update = $conn->prepare($query);

        $update->bindParam(":name", $articleName);
        $update->bindParam(":path", $path);
        $update->bindParam(":id", $pictureId);
        $result = $update->execute();
        return $result;
    }
    function get_num_of_articles($id){
        global $conn;
        $query = "SELECT COUNT(*) AS num_of_articles FROM article WHERE UserId=:id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        $result = $select->fetch();
        return $result;
    }
    
    function get_pagination_count($id){
        $result = get_num_of_articles($id);
        $num_of_articles = $result->num_of_articles;
    
        return ceil($num_of_articles / ARTICLE_OFFSET);
    }
    function get_num_of_articles_all(){
        global $conn;
        return $conn->query("SELECT COUNT(*) AS num_of_articles FROM article")->fetch();
        
    }
    function get_pagination_count_all(){
        $result = get_num_of_articles_all();
        $num_of_articles = $result->num_of_articles;
    
        return ceil($num_of_articles / ARTICLE_OFFSET);
    }
?>