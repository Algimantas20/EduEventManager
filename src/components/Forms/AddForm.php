<?php

require_once __DIR__ . "../../../Config.php";
require_once PROJECT_ROOT . "src/database.php";
require_once PROJECT_ROOT . "src/components/Input.php";

class AddForm
{
    private string $table_name;

    private const ALLOWED_TABLES =
    [
        "students",
        "events",
        "participations"
    ];

    private const STUDENT_FIELDS =
    [
        'Student ID'   => ['key' => 'student_id', 'readonly' => true],
        'First Name'   => ['key' => 'first_name', 'required' => true],
        'Last Name'    => ['key' => 'last_name', 'required' => true],
        'Date of Birth' => ['key' => 'date_of_birth', 'type' => 'date', 'required' => true],
        'Address'      => ['key' => 'address', 'required' => true],
        'Class'        => ['key' => 'class', 'required' => true],
        'Created At'   => ['key' => 'created_at', 'readonly' => true],
        'Status'       => ['key' => 'status', 'type' => 'status', 'required' => true],
    ];

    private const EVENT_FIELDS =
    [
        'Event ID'   => ['key' => 'event_id', 'readonly' => true],
        'Name'       => ['key' => 'name', 'required' => true],
        'Event Type' => ['key' => 'event_type', 'required' => true],
        'Location'   => ['key' => 'location', 'required' => true],
        'Event Date'  => ['key' => 'event_date', 'type' => 'date', 'required' => true],
        'Created At'  => ['key' => 'created_at', 'readonly' => true],
        'Status'      => ['key' => 'status', 'type' => 'status', 'required' => true],
    ];

    private const PARTICIPATION_FIELDS =
    [
        'Student'             => ['key' => 'student_id', 'type' => 'dropdown', 'required' => true],
        'Event'               => ['key' => 'event_id', 'type' => 'dropdown', 'required' => true],
        'Participation Status' => ['key' => 'participation_status', 'required' => true],
        'Created At'          => ['key' => 'created_at', 'readonly' => true],
        'Status'              => ['key' => 'status', 'type' => 'status', 'required' => true],
    ];

    public function __construct(string $table)
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new Exception("Invalid table name.");
        }

        $this->table_name = $table;
    }

    public function render(string $form_id, string $method = "POST", string $action = ""): void
    {
        echo "<form id=\"{$form_id}\" method=\"{$method}\" action=\"{$action}\" data-table=\"{$this->table_name}\">";

        $fields = $this->getFieldsForTable();
        $this->renderFields($fields);

        echo "<button id=\"submit-button\" type=\"submit\">Add</button>";
        echo "</form>";
    }

    private function generateRecordId(): string
    {
        $db = new Database();

        while (true) {
            $candidateId = random_int(1000, 9999);

            $result = $db->query(
                "SELECT 1 FROM {$this->table_name} WHERE id = {$candidateId} LIMIT 1"
            );

            if (!$result || $result->num_rows === 0) {
                $db->disconnect();
                return (string)$candidateId;
            }
        }
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

    private function renderFields(array $fields): void
    {
        foreach ($fields as $label => $config) {

            $key = $config['key'];
            $type = $config['type'] ?? 'text';

            $attributes = $this->buildAttributes($config);

            echo "<div>";
            echo "<label id=\"{$key}\">{$label}</label>";

            if ($type === 'status') {
                Input::renderDropdown($key, Config::STATUS_FIELDS);
            } else if ($type === 'dropdown') {
                Input::renderDropdown($key, $this->getDropdownValues($key));
            } else if ($key === 'created_at') {
                $this->renderDateInput($key, $attributes);
            } else if ($key === 'event_id' || $key === 'student_id') {
                Input::renderInput($key, $this->generateRecordId(), $type, $attributes);
            } else {
                Input::renderInput($key, '', $type, $attributes);
            }

            echo "</div>";
        }
    }

    private function buildAttributes(array $config): string
    {
        $attrs = [];

        if (!empty($config['readonly'])) {
            $attrs[] = "readonly";
        }

        if (!empty($config['required'])) {
            $attrs[] = "required";
        }

        return implode(" ", $attrs);
    }

    private function renderDateInput(string $key, string $attributes): void
    {
        $value = ($key === 'created_at')
            ? date('Y-m-d')
            : '';

        echo "<input type=\"date\" name=\"" . htmlspecialchars($key) . "\" value=\"" . htmlspecialchars($value) . "\" {$attributes}>";
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
        } elseif ($key === 'event_id') {
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
