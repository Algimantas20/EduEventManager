<?php

header("Content-Type: application/json");

ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . "/../../src/Config.php";
require_once PROJECT_ROOT . 'src/Operation.php';

function handleRequest()
{
    if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['table'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid ID or Table"
        ]);
    }

    try {
        Operation::delete((int)$_POST['id'], $_POST['table']);
        echo json_encode([
            "success" => true,
            "message" => "Record deleted successfully!"
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}

handleRequest();
