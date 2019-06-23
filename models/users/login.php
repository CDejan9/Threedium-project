<?php
    session_start();
    if(isset($_POST['btnLogin'])){
    //    echo "test";
        $username = $_POST['tbUsername'];
        $password = $_POST['tbPassword'];

        $errors = [];

        $reUsername = "/^[\w]+$/";
        $rePassword = "/^[0-9a-zA-Z\.\*\@\s]{5,}$/";
       
        if(!preg_match($reUsername, $username)){
            $errors[] = "Only alphanumeric characters are allowed.";
        }
        if(!preg_match($rePassword, $password)){
            $errors[] = "Only letters and numbers are allowed. The number of characters must be a minimum of 5.";
        }
        if(count($errors) != 0){
            $_SESSION['error_validation'] = $errors;
            header("Location: ../../index.php?page=login");
        }
        else{
            require_once "../../config/connection.php";
            require_once "functions.php";

            $password = md5($password);

            try{
                $isLogged = login($username, $password);
                $_SESSION['user'] = $isLogged;
                header("Location: ../../index.php?page=articles");
            }
            catch (PDOException $exception){
                $errors[] = "Error occurred when logging in.";
                $_SESSION['error_validation'] = $errors;
                header("Location: ../../index.php?page=login");
            }
        }

    }
?>