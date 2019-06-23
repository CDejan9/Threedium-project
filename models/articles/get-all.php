<?php
header("Content-type: application/json");
require_once "../../config/connection.php";
include "functions.php";

try{
    $articles = allArticles();
    echo json_encode($articles);
    http_response_code(200);
}
catch (PDOException $exception)
{
    http_response_code(500);
}
