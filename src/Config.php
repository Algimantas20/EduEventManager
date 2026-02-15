<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('PROJECT_ROOT', __DIR__ . '/../');

class Config
{
    public static string $BASE_URL = "/~PII50461LA/";
}


function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

global $student_fields;
$student_fields = [
    'Student ID'   => ['key' => 'student_id'],
    'First Name'       => ['key' => 'first_name'],
    'Last Name' => ['key' => 'last_name'],
    'Date of Birth'   => ['key' => 'date_of_birth'],
    'Address' => ['key' => 'address'],
    'Class' => ['key' => 'class'],
    'Created At' => ['key' => 'created_at'],
    'Status'     => [
        'key'   => 'status',
        'class' => 'status %s'
    ],
];

global $event_fields;
$event_fields = [
    'Event ID'   => ['key' => 'event_id'],
    'Name'       => ['key' => 'name'],
    'Event Type' => ['key' => 'event_type'],
    'Location'   => ['key' => 'location'],
    'Event Date' => ['key' => 'event_date'],
    'Created At' => ['key' => 'created_at'],
    'Status'     => [
        'key'   => 'status',
        'class' => 'status %s'
    ],
];

?>