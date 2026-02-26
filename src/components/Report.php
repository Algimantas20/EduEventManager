<?php

define("RECORDS_PER_PAGE", 10);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/database.php';
require_once PROJECT_ROOT . 'src/components/Input.php';
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
        $data = $this->getDropdownValues($this->report_type);

        $currentValue = $this->id ?? '';
        Input::renderDropdown("{$this->report_type}_id", $data, $currentValue);

        if ($this->id == null) {
            echo "<p>Please select an option to view the report.</p>";
            return;
        }

        (new ReportTable($this->report_type, $this->id))
            ->render(Config::PARTICIPATION_FIELDS, "report-table");
    }

    private function asignType($report_type): void
    {
        if (!in_array($report_type, self::ALLOWED_REPORTS, true)) {
            throw new Exception("Invalid report type.");
        }
        $this->report_type = $report_type;
    }

    private function getDropdownValues(string $key): array
    {
        if ($key === 'students') {
            $sql = "SELECT id, CONCAT(first_name,' ',last_name) AS label FROM students";
        } else if ($key === 'events') {
            $sql = "SELECT id, name AS label FROM events";
        } else {
            return [];
        }

        $result = $this->db->query($sql);
        $values = [];

        while ($row = $result->fetch_assoc()) {
            $values[$row['id']] = $row['label'];
        }

        $this->db->disconnect();

        return $values;
    }
}
