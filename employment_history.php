<?php 

// This file is not meant to be a web page. It handles data for employment history.

require_once 'config.php';

if (!isset($db)) {
    $dbs = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $db = new PDO($dbs, DB_USER, DB_PASS);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "DELETE")
{
    parse_str(file_get_contents("php://input"), $delete);

    if (!isset($delete) && (!key_exists('employment_history_id', $delete))) {
        http_response_code(400);
        exit;
    }

    $employment_history_id = $delete['employment_history_id'];

    $db->query("START TRANSACTION;");

    $statement = $db->prepare("DELETE FROM Customer_EmploymentHistory WHERE employment_history_id = ?;");
    $statement->bindParam(1, $employment_history_id, PDO::PARAM_INT);
    $statement->execute();

    $statement = $db->prepare("DELETE FROM EmploymentHistory WHERE employment_history_id = ?;");
    $statement->bindParam(1, $employment_history_id, PDO::PARAM_INT);
    $statement->execute();

    $db->query("COMMIT;");
    http_response_code(200);
    exit;
} 
else 
{
    http_response_code(404);
    exit;
}

?>