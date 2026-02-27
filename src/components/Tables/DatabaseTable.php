<?php

require_once __DIR__ . '/../../Config.php';
require_once __DIR__ . '/../../database.php';
require_once PROJECT_ROOT . "src/components/Input.php";

class DatabaseTable
{
    private string $table_name;
    private Database $db;

    public function __construct(string $table_name)
    {
        $this->db = new Database();
        $this->table_name = $table_name;
    }

    public function __destruct()
    {
        $this->db->disconnect();
    }

    private function getTotalPages(): int
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM `{$this->table_name}`");
        $total  = $result->fetch_assoc()['total'] ?? 0;

        return max(1, (int) ceil($total / RECORDS_PER_PAGE));
    }

    private function getGroupBy(): string
    {
        $groupBy = $_GET['group-by'] ?? null;

        $allowed = Config::ALLOWED_SORTS[$this->table_name] ?? [];

        if ($groupBy && isset($allowed[$groupBy])) {
            $column = $allowed[$groupBy]['column'];
            return "ORDER BY {$column} DESC";
        }

        return "ORDER BY created_at DESC";
    }

    private function getCurrentPage()
    {
        $page = $_GET['page'] ?? null;

        return isset($page) && is_numeric($page) ? max(1, (int) $page) : 1;
    }

    private function getPageContent(array $fields): mysqli_result
    {
        $page = $this->getCurrentPage();
        $sortBy = $this->getGroupBy();
        $offset = ($page - 1) * RECORDS_PER_PAGE;

        if ($this->table_name === "participations") {
            $sql = "
            SELECT 
                p.id,
                CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                e.name AS event,
                p.participation_status,
                p.created_at,
                p.status
            FROM participations p
            JOIN students s ON p.student_id = s.id
            JOIN events e ON p.event_id = e.id
            $sortBy
            LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset";
        } else {
            $sql = "
            SELECT *
            FROM `{$this->table_name}`
            $sortBy
            LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset";
        }

        return $this->db->query($sql);
    }

    private function renderRow(array $row, array $fields): void
    {
        foreach ($fields as $label => $config) {
            $key   = $config['key'];
            $class = $config['class'] ?? '';
            $value = $row[$key] ?? '';

            if ($key === 'participation_status') {
                $value = Config::PARTICIPATION_STATUS[$value] ?? $value;
            }

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
            $id = (int) $row['id'];
            $table = h($this->table_name);

            echo '<tr>';
            $this->renderRow($row, $fields);
            echo '<td class="actions">';
            echo "<a class=\"edit-link\" data-id=\"$id\" data-table=\"$table\">Edit</a>";
            echo "<a class=\"danger delete-link\" data-id=\"$id\" data-table=\"$table\">Delete</a>";
            echo '</td>';
            echo '</tr>';
        }
    }

    private function renderHeader(array $fields): void
    {
        echo '<tr>';
        foreach ($fields as $label => $config) {
            echo '<th data-label="' . h($label) . '">' . h($label) . '</th>';
        }
        echo '<th data-label="Actions"></th>';
        echo '</tr>';
    }

    public function getTotalRecordCount()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total_count FROM {$this->table_name}");
        $row = $query->fetch_assoc();

        return (int) $row['total_count'];
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
        $this->pagination();
        echo '</div>';
    }

    public function pagination(): void
    {
        $currentPage = $this->getCurrentPage();
        $totalPages  = $this->getTotalPages();

        $queryParams = $_GET;

        if ($currentPage > 1) {
            $queryParams['page'] = $currentPage - 1;
            $url = '?' . http_build_query($queryParams);

            echo '<a href="' . h($url) . '">&laquo; Prev</a>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            $queryParams['page'] = $i;
            $url = '?' . http_build_query($queryParams);

            echo '<a href="' . h($url) . '" class="'
                . ($i === $currentPage ? 'active' : '') . '">'
                . $i
                . '</a>';
        }

        if ($currentPage < $totalPages) {
            $queryParams['page'] = $currentPage + 1;
            $url = '?' . http_build_query($queryParams);

            echo '<a href="' . h($url) . '">Next &raquo;</a>';
        }
    }

    public function renderGroupBy()
    {
        $currentValue = $_GET["group-by"] ?? '';
        Input::renderDropdown("group-by", Config::ALLOWED_SORTS[$this->table_name], $currentValue);
    }

    public function showReports()
    {
        $options = [
            'student' => 'Student Reports',
            'event' => 'Event Reports'
        ];

        echo "<section class=\"report-container\">";
        echo "<select id=\"sort-by\">";
        echo "<option disabled selected hidden>Watch Reports</option>";
        foreach ($options as $value => $label) {
            echo "<option value=\"$value\">$label</option>";
        }
        echo "</select>";
        echo "</section>";
    }
}
