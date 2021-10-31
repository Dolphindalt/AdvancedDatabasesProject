<?php 

// This file is not meant to be a web page. It handles data for vehicles.

require_once 'config.php';

if (!isset($db)) {
    $dbs = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $db = new PDO($dbs, DB_USER, DB_PASS);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "POST")
{
    if (!isset($_POST)) {
        http_response_code(400);
        exit;
    }

    if (key_exists('vin', $_POST)) {
        $vin = $_POST['vin'];
        $statement = $db->prepare("SELECT * FROM Vehicle WHERE vin = ?;");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
        $statement->execute();
        $vehicle_info = $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($vehicle_info);
        http_response_code(200);
        exit;
    }

} 
    http_response_code(404);
    exit;

?>