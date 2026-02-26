<?php

require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../database.php';

class ReportTable
{
    private string $table_name;
    private int $id;

    private Database $db;

    public function __construct(string $table_name, int $id)
    {
        $this->db = new Database();
        $this->table_name = $table_name;
        $this->id = (int) $id;
    }

    public function __destruct()
    {
        $this->db->disconnect();
    }

    private function buildWhere(): string
    {
        if ($this->table_name === "events") {
            return "WHERE p.event_id = {$this->id}";
        }

        if ($this->table_name === "students") {
            return "WHERE p.student_id = {$this->id}";
        }

        return "";
    }

    private function getCurrentPage()
    {
        $page = $_GET['page'] ?? null;
        return isset($page) && is_numeric($page) ? max(1, (int)$page) : 1;
    }

    private function getPageContent(array $fields): mysqli_result
    {
        $page = $this->getCurrentPage();
        $offset = ($page - 1) * RECORDS_PER_PAGE;
        $where = $this->buildWhere();

        $sql = "SELECT 
                    p.id,
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                    e.name AS event_name,
                    p.participation_status,
                    p.created_at,
                    p.status
                FROM participations p
                JOIN students s ON p.student_id = s.id
                JOIN events e ON p.event_id = e.id
                $where
                ORDER BY p.created_at DESC
                LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset";

        return $this->db->query($sql);
    }

    private function renderRow(array $row, array $fields): void
    {
        foreach ($fields as $label => $config) {
            $key   = $config['key'];
            $class = $config['class'] ?? '';
            $value = $row[$key] ?? '';

            $classAttr = $class
                ? ' class="' . h(sprintf($class, $value)) . '"'
                : '';

            echo '<td data-label="' . h($label) . '"' . $classAttr . '>'
                . h($value)
                . '</td>';
        }
    }

    private function renderBody(array $fields): void
    {
        $result = $this->getPageContent($fields);

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            $this->renderRow($row, $fields);
            echo '</tr>';
        }
    }

    private function renderHeader(array $fields): void
    {
        echo '<tr>';
        foreach ($fields as $label => $config) {
            echo '<th data-label="' . h($label) . '">' . h($label) . '</th>';
        }
        echo '</tr>';
    }

    public function render(array $fields, string $class_name): void
    {
        echo '<div class="table-container">';
        echo '<table class="' . h($class_name) . '">';

        echo '<thead>';
        $this->renderHeader($fields);
        echo '</thead>';

        echo '<tbody>';
        $this->renderBody($fields);
        echo '</tbody>';

        echo '</table>';
        echo '</div>';

        echo '<div class="pagination">';
        echo '</div>';
    }
}
