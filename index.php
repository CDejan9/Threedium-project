<?php
    session_start();
    require_once "config/connection.php";
    include "views/fixed/head.php";

    if(isset($_GET['page'])){
        switch (($_GET['page'])){
            case 'login':
                include "views/pages/login.php";
                break;
            case 'articles':
                include "views/pages/articles.php";
                break;
            case 'profile':
                include "views/pages/profile.php";
                break;
            case 'single-article':
                include "views/pages/single-article.php";
                break;    
            case 'show-update':
                include "views/pages/show-update-data-article.php";
                break;
            default:
                include "views/pages/login.php";
        }
    } 
    else {
        include "views/pages/login.php";
    }

    include "views/fixed/footer.php";
?>