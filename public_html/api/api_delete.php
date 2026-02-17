<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../src/Config.php";
require_once PROJECT_ROOT . 'src/Operation.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['table'])) {
    http_response_code(400);
    exit("Missing ID or Table");
}

try {
    Operation::delete((int)$_POST['id'], $_POST['table']);
    echo "Record successfully deleted";
} catch (Exception $e) {
    exit($e->getMessage());
}
