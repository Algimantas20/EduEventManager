<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('PROJECT_ROOT', __DIR__ . '/../');

class Config
{
    public static string $BASE_URL = "/~PII50461LA/";

    public const PARTICIPATION_FIELDS =
    [
        'Student'               => ['key' => 'student_name'],   // resolved via JOIN (e.g., CONCAT(first_name, " ", last_name))
        'Event'                 => ['key' => 'event_name'],          // resolved via JOIN from events table
        'Participation Status'  => ['key' => 'participation_status'],
        'Created At'            => ['key' => 'created_at'],
        'Status' => [
            'key'   => 'status',
            'class' => 'status %s'
        ]
    ];

    public const STUDENT_FIELDS =
    [
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

    public const EVENT_FIELDS =
    [
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

    public const STATUS_FIELDS =
    [
        'A' => 'Active',
        'I' => 'Inactive',
        'D' => 'Deleted'
    ];

    public const PARTICIPATION_STATUS =
    [
        'registered' => 'Registered',
        'cancelled' => 'Cancelled',
        'participated' => 'Participated',
        'not_participated' => 'Didn\'t Participate'
    ];

    public const ALLOWED_SORTS = [
        'students' => [
            'created_at' => [
                'column' => 'created_at',
                'label'  => 'Created At'
            ],
            'class' => [
                'column' => 'class',
                'label'  => 'Class'
            ]
        ],
        'participations' => [
            'created_at' => [
                'column' => 'p.created_at',
                'label'  => 'Created At'
            ],
            'event' => [
                'column' => 'e.name',
                'label'  => 'Event Name'
            ],
            'activity' => [
                'column' => 'activity',
                'label' => 'Activty'
            ]
        ],
        'events' => [
            'created_at' => [
                'column' => 'created_at',
                'label'  => 'Created At'
            ],
            'location' => [
                'column' => 'location',
                'label' => 'Location'
            ]
        ]
    ];
}


function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
