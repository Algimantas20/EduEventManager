<?php

require_once __DIR__ . "../../../Config.php";
require_once PROJECT_ROOT . "src/database.php";

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

    public function render(string $form_id, string $method = "POST", string $action = ""): void
    {
        echo "<form id=\"{$form_id}\" method=\"{$method}\" action=\"{$action}\" data-table=\"{$this->table_name}\" >";

        $fields = $this->getFieldsForTable();
        $this->renderFields($fields);

        echo "<button id=\"submit-button\" type=\"submit\">Add</button>";
        echo "</form>";
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
            $isReadonly = !empty($config['readonly']);

            $key  = $config['key'];
            $type = $config['type'] ?? 'text';

            echo "<div>";
            echo "<label>{$label}</label>";

            if ($type === 'status') {
                $this->renderStatusInput($key, $isReadonly);
            } else if ($key === 'created_at') {
                $this->renderDateInput($key, $isReadonly);
            } else {
                $readonlyAttr = $isReadonly ? ' readonly' : '';
                echo "<input type=\"{$type}\" name=\"{$key}\" value=\"\"{$readonlyAttr}>";
            }

            echo "</div>";
        }
    }


    private function renderDateInput(string $key, bool $readonly): void
    {
        $readonlyAttr = $readonly ? ' readonly' : '';

        $value = ($key === 'created_at')
            ? date('Y-m-d')
            : '';

        echo "<input 
            type=\"date\" 
            name=\"" . htmlspecialchars($key) . "\" 
            value=\"" . htmlspecialchars($value) . "\"{$readonlyAttr}>";
    }

    private function renderStatusInput(string $key, bool $readonly): void
    {
        $options = [
            'A' => 'Active',
            'I' => 'Inactive',
            'D' => 'Deleted'
        ];

        $disabledAttr = $readonly ? ' disabled' : '';

        echo "<select name=\"{$key}\"{$disabledAttr}>";

        foreach ($options as $value => $label) {
            echo "<option value=\"{$value}\">{$label}</option>";
        }

        echo "</select>";
    }
}
