<?php

require_once __DIR__ . "/../../src/Config.php";
require_once PROJECT_ROOT . 'src/Operation.php';

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
    $table = $_POST['table'] ?? '';

    $data = $_POST;
    unset($data['table']);

    validateData($table, $data);

    try {
        Operation::create($table, $data);
        echo "Record created successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

handleRequest();
