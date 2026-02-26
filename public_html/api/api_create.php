<?php
header('Content-Type: application/json');

require_once __DIR__ . "/../../src/Config.php";
require_once PROJECT_ROOT . 'src/Operation.php';
require_once PROJECT_ROOT . 'src/database.php';

function validateStudentData($data)
{
    if ($data['date_of_birth'] > $data['created_at']) {
        throw new Exception("Date of Birth cannot be in the future.");
    }
}

function validateEventData($data)
{
    if ($data['event_date'] < $data['created_at']) {
        throw new Exception("Event Date cannot be in the past.");
    }
}

function validateParicipationData($data)
{
    $db = new Database();
    $student_id = $data['student_id'];
    $event_id = $data['event_id'];

    $result = $db->query("SELECT 1 FROM participations WHERE student_id = {$student_id} AND event_id = {$event_id}");
    if ($result && $result->num_rows > 0) {
        throw new Exception("Student is already participating in this event.");
    }
}

function validateData($table, $data)
{
    if ($table === 'students') {
        validateStudentData($data);
    } else if ($table === 'events') {
        validateEventData($data);
    } else if ($table === 'participations') {
        validateParicipationData($data);
    }
}

function handleRequest()
{
    try {
        $table = $_POST['table'] ?? '';

        $data = $_POST;
        unset($data['table']);

        validateData($table, $data);

        Operation::create($table, $data);

        echo json_encode([
            "success" => true,
            "message" => "Record created successfully!"
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
