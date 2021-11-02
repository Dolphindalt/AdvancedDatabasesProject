<?php 

// This file is not meant to be a web page. It handles data for customers.

require_once 'config.php';

if (!isset($db)) {
    $dbs = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $db = new PDO($dbs, DB_USER, DB_PASS);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "POST")
{
    if (!isset($_POST) && (!key_exists('tax_id', $_POST))) {
        http_response_code(400);
        exit;
    }

    $tax_id = $_POST['tax_id'];

    $statement = $db->prepare("SELECT * FROM Customer WHERE tax_id = ?;");
    $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
    $statement->execute();
    $customer = $statement->fetchAll(PDO::FETCH_ASSOC)[0];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($customer);
    http_response_code(200);
    exit;
} 
else 
{
    http_response_code(404);
    exit;
}

?>