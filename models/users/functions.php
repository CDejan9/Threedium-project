<?php
    function login($username, $password){
        global $conn;
        $query = "SELECT * FROM user WHERE Username=:user AND Password=:pass";
        $select = $conn->prepare($query);

        $select->bindParam(":user", $username);
        $select->bindParam(":pass", $password);
        $select->execute();
        
        $result = $select->fetch();
        return $result;
    }

    function listOfUsers(){
        global $conn;
        $users = $conn->query("SELECT * FROM user")->fetchAll();
        return $users;
    }
    function userInfo($id){
        global $conn;
        $query = "SELECT * FROM user WHERE UserId = :id";
        $select = $conn->prepare($query);

        $select->bindParam(":id", $id);
        $select->execute();
        
        $result = $select->fetch();
        return $result;
    }
?>