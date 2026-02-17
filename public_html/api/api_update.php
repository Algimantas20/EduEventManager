<?php

require_once __DIR__ . "/../../src/Config.php";
require_once __DIR__ . "/../../src/Operation.php";

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$table = $_POST['table'] ?? '';

$data = $_POST;
unset($data['id'], $data['table']);

try {
    Operation::update($table, (int)$id, $data);
    echo "Record updated successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
