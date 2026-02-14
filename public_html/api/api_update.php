<?php
require_once __DIR__ . '/../../src/operations/update.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$table = $_POST['table'] ?? '';

$data = $_POST;
unset($data['id'], $data['table']);

try 
{
    UpdateRecord($table, $id, $data);
    echo "Record updated successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>