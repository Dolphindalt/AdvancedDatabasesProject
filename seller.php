<?php 

// This file is not meant to be a web page. It handles data for sellers.

require_once 'config.php';

if (!isset($db)) {
    $dbs = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $db = new PDO($dbs, DB_USER, DB_PASS);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "POST")
{
    if (!isset($_POST) && (!key_exists('seller_tax_id', $_POST) || !key_exists('name', $_POST))) {
        http_response_code(400);
        exit;
    }

    $seller_tax_id = $_POST['seller_tax_id'];
    $name = $_POST['name'];

    $statement = $db->prepare("INSERT INTO Seller (seller_tax_id, name) VALUES (?, ?);");
    $statement->bindParam(1, $seller_tax_id, PDO::PARAM_INT);
    $statement->bindParam(2, $name, PDO::PARAM_STR);
    $statement->execute();
    http_response_code(201);
    exit;
} 
else 
{
    http_response_code(404);
    exit;
}

?>