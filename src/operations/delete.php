<?php
require_once __DIR__ . '/../database.php';

function operationDelete(int $id, string $table_name) {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
