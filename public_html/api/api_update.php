<?php
header('Content-Type: application/json');

require_once __DIR__ . "/../../src/Config.php";
require_once __DIR__ . "/../../src/Operation.php";

function validateData($table, $data)
{
    if ($table === 'students') {
        if ($data['date_of_birth'] > $data['created_at']) {
            throw new Exception("Date of Birth cannot be in the future.");
        }
    } else if ($table === 'events') {
        if ($data['event_date'] < $data['created_at']) {
            throw new Exception("Event Date cannot be in the past.");
        }
    }
}

function handleRequest()
{
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $table = $_POST['table'] ?? '';

    $data = $_POST;
    unset($data['id'], $data['table']);

    validateData($table, $data);

    try {
        Operation::update($table, (int)$id, $data);
        echo json_encode([
            "success" => true,
            "message" => "Record updated successfully!"
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
