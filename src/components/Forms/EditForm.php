<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "../../../Config.php";
require_once PROJECT_ROOT . "src/database.php";
require_once PROJECT_ROOT . "src/components/Input.php";

class EditForm
{
    private Database $db;
    private string $table_name;
    private int $record_id;

    private const ALLOWED_TABLES =
    [
        "students",
        "events",
        "participations"
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
        'Student'              => ['key' => 'student_id', 'type' => 'dropdown', 'required' => true],
        'Event'                => ['key' => 'event_id', 'type' => 'dropdown', 'required' => true],
        'Participation Status' => ['key' => 'participation_status', 'required' => true],
        'Created At'           => ['key' => 'created_at', 'readonly' => true],
        'Status'               => ['key' => 'status', 'type' => 'status', 'required' => true],
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

        Input::renderInput("id", $this->record_id, "hidden", false);
        Input::renderInput("table", $this->table_name, "hidden", false);

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
            "students" => self::STUDENT_FIELDS,
            "events" => self::EVENT_FIELDS,
            "participations" => self::PARTICIPATION_FIELDS,
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

            $value = $record[$key] ?? '';
            $type = $config['type'] ?? 'text';
            $readadonly = !empty($config['readonly']) ? "readonly" : '';

            echo "<div>";
            echo "<label>{$label}</label>";

            if ($type === 'status') {
                Input::renderDropdown($key, Config::STATUS_FIELDS, $value);
            } else if ($type === 'dropdown') {
                Input::renderDropdown($key, $this->getDropdownValues($key), $value);
            } else {
                Input::renderInput($key, $value, $type, $readadonly);
            }

            echo "</div>";
        }
    }

    private function getDropdownValues(string $key): array
    {
        $db = new Database();

        if ($key === 'student_id') {
            $sql = "
                SELECT id, CONCAT(first_name, ' ', last_name) AS label
                FROM students
                WHERE status = 'A'
                ORDER BY first_name ASC
            ";
        } else if ($key === 'event_id') {
            $sql = "
                SELECT id, name AS label
                FROM events
                WHERE status = 'A'
                ORDER BY name ASC
            ";
        } else {
            return [];
        }

        $result = $db->query($sql);
        $values = [];

        while ($row = $result->fetch_assoc()) {
            $values[$row['id']] = $row['label'];
        }

        $db->disconnect();

        return $values;
    }
}
