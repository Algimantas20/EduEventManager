<?php
define('RECORDS_PER_PAGE', 15);

require_once '../src/database.php';

$db   = new Database();
$conn = $db->connect();

$fields = [
    'ID'         => ['key' => 'id'],
    'Event ID'   => ['key' => 'event_id'],
    'Name'       => ['key' => 'name'],
    'Event Type' => ['key' => 'event_type'],
    'Location'   => ['key' => 'location'],
    'Event Date' => ['key' => 'event_date'],
    'Created At' => ['key' => 'created_at'],
    'Status'     => [
        'key'   => 'status',
        'class' => 'status %s'
    ],
];

function getTotalPages(mysqli $conn, Database $db): int
{
    $sql    = "SELECT COUNT(*) AS total FROM Event";
    $result = $db->query($conn, $sql);
    $total  = $result->fetch_assoc()['total'] ?? 0;

    return max(1, (int) ceil($total / RECORDS_PER_PAGE));
}

function getEventTable(mysqli $conn, Database $db, int $page): mysqli_result
{
    $offset = ($page - 1) * RECORDS_PER_PAGE;

    $sql = " SELECT * FROM Event ORDER BY created_at DESC LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset";

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


function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int) $_GET['page']): 1;

$totalPages = getTotalPages($conn, $db);
$result = getEventTable($conn, $db, $page);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EduEventManager – Events</title>

    <link rel="stylesheet" href="styles/components/header.css">
    <link rel="stylesheet" href="styles/components/table.css">
    <link rel="stylesheet" href="styles/events.css">
</head>
<body>

<header>
    <h1>EduEventManager</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="#" aria-current="page">Events</a>
    </nav>
</header>

<main class="events-page">

<?php if ($result && $result->num_rows > 0): ?>
    <div class="table-container">
        <table class="event-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event ID</th>
                    <th>Name</th>
                    <th>Event Type</th>
                    <th>Location</th>
                    <th>Event Date</th>
                    <th>Created At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?= renderRow($row, $fields); ?>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" 
                class="<?= $i === $page ? 'active' : '' ?>"> 
                <?= $i ?> 
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>

<?php else: ?>
    <p class="empty-state">No events found.</p>
<?php endif; ?>

</main>

</body>
</html>

<?php $db->disconnect($conn); ?>
