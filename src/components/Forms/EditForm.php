<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "../../../Config.php";
require_once PROJECT_ROOT . "src/database.php";

class EditForm
{
    private Database $db;
    private string $table_name;
    private int $record_id;

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

    public function __construct(string $table, int $record_id)
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new Exception("Invalid table name.");
        }

        if ($record_id <= 0) {
            throw new Exception("Invalid record ID.");
        }

        $this->db = new Database();
        $this->table_name = $table;
        $this->record_id = $record_id;
    }

    public function render(string $form_id, string $method, string $action): void
    {
        echo "<form id=\"{$form_id}\" method=\"{$method}\" action=\"{$action}\">";

        $record = $this->getRecord();
        $fields = $this->getFieldsForTable();

        $this->renderFields($record, $fields);

        echo "<input type=\"hidden\" name=\"id\" value=\"{$this->record_id}\">";
        echo "<input type=\"hidden\" name=\"table\" value=\"{$this->table_name}\">";
        echo "<button class=\"update-link\"type=\"submit\">Save</button>";

        echo "</form>";
    }

    private function getRecord(): array
    {
        $result = $this->db->query("SELECT * FROM `{$this->table_name}` WHERE id = {$this->record_id} LIMIT 1");
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception("Record not found.");
        }

        return $row;
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

    private function renderFields(array $record, array $fields): void
    {
        foreach ($fields as $label => $config) {
            $key = $config['key'];

            if (!array_key_exists($key, $record)) {
                continue;
            }

            $value = h((string)$record[$key]);
            $type = $config['type'] ?? 'text';
            $readonly = !empty($config['readonly']) ? 'readonly' : '';

            echo "<div>";
            echo "<label>{$label}</label>";

            if ($type === 'status') {
                $this->renderStatusInput($value);
            } else {
                echo "<input type=\"{$type}\" name=\"{$key}\" value=\"{$value}\" {$readonly}>";
            }

            echo "</div>";
        }
    }

    private function renderStatusInput(string $currentValue): void
    {
        $options =
            [
                'A' => 'Active',
                'I' => 'Inactive',
                'D' => 'Deleted'
            ];

        echo "<select name=\"status\">";

        foreach ($options as $value => $label) {
            $selected = ($value === $currentValue) ? "selected" : "";
            echo "<option value=\"{$value}\" {$selected}>{$label}</option>";
        }

        echo "</select>";
    }
}
