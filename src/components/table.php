<?php

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function getTotalPages(mysqli $conn, Database $db, string $table_name): int
{
    $sql    = "SELECT COUNT(*) AS total FROM " . $table_name;
    $result = $db->query($conn, $sql);
    $total  = $result->fetch_assoc()['total'] ?? 0;

    return max(1, (int) ceil($total / RECORDS_PER_PAGE));
}

function getCurrentPage()
{
    $page = $_GET['page'] ?? null;

    return isset($page) && is_numeric($page) ? max(1, (int) $page): 1;
}

function getEventTable(mysqli $conn, Database $db, string $table_name, int $page): mysqli_result
{
    $offset = ($page - 1) * RECORDS_PER_PAGE;

    $sql = " SELECT * FROM " . $table_name . " ORDER BY created_at DESC LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset";

    return $db->query($conn, $sql);
}

function renderRow(array $row, array $fields): void
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

function renderTableBody(mysqli_result $result, array $fields): void
{
    $table_name = $result->fetch_fields()[0]->table;

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        renderRow($row, $fields);
        echo '<td class="actions">';
        echo '<a>Edit</a>';
        echo '<a class="danger" onclick="deleteUser(' . $row['id'] . ', \'' . $table_name . '\')">Delete</a>';
        echo '</td>';
        echo '</tr>';
    }
}

function renderTableHeader(array $fields): void
{
    echo '<tr>';
    foreach ($fields as $label => $config) {
        echo '<th data-label="' . h($label) . '">'
            . h($label)
            . '</th>';
    }
    echo '<th data-label="Actions"></th>';
    echo '</tr>';
}

function renderTable(array $fields, mysqli_result $result): void
{
    echo '<thead>';
    renderTableHeader($fields);
    echo '</thead>';

    echo '<tbody>';
    renderTableBody($result, $fields);
    echo '</tbody>';
}

function renderPagination(int $currentPage, int $totalPages): void
{
    if ($currentPage > 1)
    {
        echo '<a href="?page=' . ($currentPage - 1) . '">&laquo; Prev</a>';
    }

    for ($i = 1; $i <= $totalPages; $i++)
    {
        echo '<a href="?page=' . $i . '" class="'
            . ($i === $currentPage ? 'active' : '') . '">'
            . $i
            . '</a>';
    }

    if ($currentPage < $totalPages)
    {
        echo '<a href="?page=' . ($currentPage + 1) . '">Next &raquo;</a>';
    }
}

?>