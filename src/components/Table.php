<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Config.php';
require_once __DIR__ . '/../database.php';

class Table
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

    private function getCurrentPage()
    {
        $page = $_GET['page'] ?? null;

        return isset($page) && is_numeric($page) ? max(1, (int) $page) : 1;
    }

    private function getPageContent(): mysqli_result
    {
        $page = $this->getCurrentPage();
        $offset = ($page - 1) * RECORDS_PER_PAGE;

        $sql = " SELECT * FROM `{$this->table_name}` ORDER BY created_at DESC LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset";

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
        $result = $this->getPageContent();
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
        if ($currentPage > 1) {
            echo '<a href="?page=' . ($currentPage - 1) . '">&laquo; Prev</a>';
        }

        for ($i = 1; $i <= $this->getTotalPages(); $i++) {
            echo '<a href="?page=' . $i . '" class="'
                . ($i === $currentPage ? 'active' : '') . '">'
                . $i
                . '</a>';
        }

        if ($currentPage < $this->getTotalPages()) {
            echo '<a href="?page=' . ($currentPage + 1) . '">Next &raquo;</a>';
        }
    }
}
