<?php

define("RECORDS_PER_PAGE", 10);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/database.php';
require_once PROJECT_ROOT . 'src/components/Tables/ReportTable.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Report
{
    private const ALLOWED_REPORTS = ['students', 'events'];

    private ?int $id;
    private string $report_type;

    private Database $db;

    public function __construct(string $report_type, ?int $id = null)
    {
        $this->asignType($report_type);
        $this->id = $id;
        $this->db = new Database();
    }

    public function render(): void
    {
        $this->renderSelect();

        if ($this->id == null) {
            echo "<p>Please select an option to view the report.</p>";
            return;
        }

        (new ReportTable($this->report_type, $this->id))->render(Config::PARTICIPATION_FIELDS, "report-table");
    }

    private function asignType($report_type): void
    {
        if (!in_array($report_type, self::ALLOWED_REPORTS, true)) {
            throw new Exception("Invalid report type.");
        }
        $this->report_type = $report_type;
    }

    private function renderSelect(): void
    {
        if ($this->report_type === 'students') {
            $this->renderStudentSelect();
        } else if ($this->report_type === 'events') {
            $this->renderEventSelect();
        } else {
            echo "<p>Unsupported report type.</p>";
        }
    }

    private function renderStudentSelect(): void
    {
        $students = $this->db->query(
            "SELECT id, CONCAT(first_name,' ',last_name) AS name FROM students"
        );

        echo '<select id="student_id">';
        echo '<option disabled selected hidden>Select Student</option>';

        foreach ($students as $student) {
            echo '<option value="' . h($student['id']) . '">'
                . h($student['name'])
                . '</option>';
        }

        echo '</select>';
    }

    private function renderEventSelect(): void
    {
        $events = $this->db->query(
            "SELECT id, name FROM events"
        );

        echo '<select id="event_id">';
        echo '<option disabled selected hidden>Select Event</option>';

        foreach ($events as $event) {
            echo '<option value="' . h($event['id']) . '">'
                . h($event['name'])
                . '</option>';
        }

        echo '</select>';
    }
}
