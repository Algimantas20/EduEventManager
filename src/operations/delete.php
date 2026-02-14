<?php
require_once __DIR__ . '/../database.php';

function operationDelete(int $id, string $table_name) 
{
    $db = new Database();   
    $db->query("DELETE FROM `$table_name` WHERE id=?", [$id]);
    $db->disconnect();
}
