<?php

require_once __DIR__ . '/../../src/operations/delete.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['table'])) {
    http_response_code(400);
    exit("Missing ID or Table");
}

operationDelete((int)$_POST['id'], $_POST['table']);
echo "Record successfully deleted";

?>