<?php
define('RECORDS_PER_PAGE', 10);

require_once '../src/database.php';
require_once '../src/components/table.php';
require_once '../src/components/header.php';

$fields = [
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

$db   = new Database();
$conn = $db->connect();

$page = getCurrentPage();
$totalPages = getTotalPages($conn, $db, "Event");
$result = getEventTable($conn, $db, "Event", $page);

$db->disconnect($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="styles/components/header.css">
    <link rel="stylesheet" href="styles/components/table.css">
    <link rel="stylesheet" href="styles/events.css">

    <title>EduEventManager - Events</title>
</head>
<body>

<?php renderPageHeader('Events'); ?>

<main class="events-page">

<?php if ($result && $result->num_rows > 0): ?>
    <div class="table-container">
        <table class="event-table">
            <?= renderTable($fields, $result); ?>
        </table>
    </div>

    <div class="pagination">
        <?= renderPagination($page, $totalPages); ?>
    </div>

<?php else: ?>
    <p class="empty-state">No events found.</p>
<?php endif; ?>

</main>

<script src="js/app.js"></script>

</body>
</html>
