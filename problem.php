<?php 

// This file is not meant to be a web page. It handles data for problems.

require_once 'config.php';

if (!isset($db)) {
    $dbs = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $db = new PDO($dbs, DB_USER, DB_PASS);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "PATCH")
{
    parse_str(file_get_contents("php://input"), $patch);

    if (!key_exists('problem_id', $patch) || !key_exists('description', $patch) || !key_exists('actual_repair_cost', $patch)) {
        http_response_code(400);
        exit;
    }

    $problem_id = $patch['problem_id'];
    $description = $patch['description'];
    $actual_repair_cost = $patch['actual_repair_cost'];

    $statement = $db->prepare("UPDATE Problem SET description = ?, actual_repair_cost = ? WHERE problem_id = ?;");
    $statement->bindParam(1, $description, PDO::PARAM_STR);
    $statement->bindParam(2, $actual_repair_cost, PDO::PARAM_STR);
    $statement->bindParam(3, $problem_id, PDO::PARAM_INT);
    $statement->execute();

    http_response_code(204);
    exit;
} 
else if ($method == "DELETE")
{
    parse_str(file_get_contents("php://input"), $delete);

    if (!key_exists('problem_id', $delete)) {
        http_response_code(400);
        exit;
    }

    $problem_id = $delete['problem_id'];

    $db->query("START TRANSACTION;")->execute();

    $statement = $db->prepare("DELETE FROM Vehicle_Problem WHERE problem_id = ?;");
    $statement->bindParam(1, $problem_id, PDO::PARAM_INT);
    $statement->execute();

    $statement = $db->prepare("DELETE FROM Problem WHERE problem_id = ?;");
    $statement->bindParam(1, $problem_id, PDO::PARAM_INT);
    $statement->execute();

    $db->query("COMMIT;")->execute();

    http_response_code(204);
    exit;
}
else 
{
    http_response_code(404);
    exit;
}

?>