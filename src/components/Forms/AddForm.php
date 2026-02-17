<?php

require_once __DIR__ . "../../../Config.php";
require_once PROJECT_ROOT . "src/database.php";

class AddForm
{
    private string $table_name;

    private const ALLOWED_TABLES =
    [
        "Student",
        "Event",
        "Participation"
    ];

    private const STUDENT_FIELDS =
    [
        'Student ID'   => ['key' => 'student_id', 'readonly' => true],
        'First Name'   => ['key' => 'first_name'],
        'Last Name'    => ['key' => 'last_name'],
        'Date of Birth' => ['key' => 'date_of_birth', 'type' => 'date'],
        'Address'      => ['key' => 'address'],
        'Class'        => ['key' => 'class'],
        'Created At'   => ['key' => 'created_at', 'readonly' => true],
        'Status'       => ['key' => 'status', 'type' => 'status'],
    ];

    private const EVENT_FIELDS =
    [
        'Event ID'     => ['key' => 'event_id', 'readonly' => true],
        'Name'         => ['key' => 'name'],
        'Event Type'   => ['key' => 'event_type'],
        'Location'     => ['key' => 'location'],
        'Event Date'   => ['key' => 'event_date', 'type' => 'date'],
        'Created At'   => ['key' => 'created_at', 'readonly' => true],
        'Status'       => ['key' => 'status', 'type' => 'status'],
    ];

    private const PARTICIPATION_FIELDS =
    [
        'Participation ID' => ['key' => 'participation_id', 'readonly' => true],
        'Student ID'       => ['key' => 'student_id'],
        'Event ID'         => ['key' => 'event_id'],
        'Created At'       => ['key' => 'created_at', 'readonly' => true],
        'Status'           => ['key' => 'status', 'type' => 'status'],
    ];

    public function __construct(string $table)
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new Exception("Invalid table name.");
        }

        $this->table_name = $table;
    }

    public function render(string $form_id, string $method, string $action): void
    {
        echo "<form id=\"{$form_id}\" method=\"{$method}\" action=\"{$action}\">";

        $fields = $this->getFieldsForTable();
        $this->renderFields($fields);

        echo "<button type=\"submit\">Add</button>";
        echo "</form>";
    }

    private function getFieldsForTable(): array
    {
        return match ($this->table_name) {
            "Student" => self::STUDENT_FIELDS,
            "Event" => self::EVENT_FIELDS,
            "Participation" => self::PARTICIPATION_FIELDS,
            default => throw new Exception("Unknown table.")
        };
    }

    private function renderFields(array $fields): void
    {
        foreach ($fields as $label => $config) {
            if (!empty($config['readonly'])) {
                continue;
            }

            $key = $config['key'];
            $type = $config['type'] ?? 'text';

            echo "<div>";
            echo "<label>{$label}</label>";

            if ($type === 'status') {
                $this->renderStatusInput();
            } else {
                echo "<input type=\"{$type}\" name=\"{$key}\" value=\"\">";
            }

            echo "</div>";
        }
    }

    private function renderStatusInput(): void
    {
        $options =
            [
                'A' => 'Active',
                'I' => 'Inactive',
                'D' => 'Deleted'
            ];

        echo "<select name=\"status\">";

        foreach ($options as $value => $label) {
            echo "<option value=\"{$value}\">{$label}</option>";
        }

        echo "</select>";
    }
}
