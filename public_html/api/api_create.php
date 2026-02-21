<?php

require_once __DIR__ . "/../../src/Config.php";
require_once PROJECT_ROOT . 'src/Operation.php';

$table = $_POST['table'] ?? '';

$data = $_POST;
unset($data['table']);

try {
    Operation::create($table, $data);
    echo "Record created successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
