<?php
define('RECORDS_PER_PAGE', 15);

require_once '../src/database.php';
require_once '../src/table.php';

$fields = [
    'ID'         => ['key' => 'id'],
    'Student ID'   => ['key' => 'student_id'],
    'First Name'       => ['key' => 'first_name'],
    'Last Name' => ['key' => 'last_name'],
    'Date of Birth'   => ['key' => 'date_of_birth'],
    'Address' => ['key' => 'address'],
    'Class' => ['key' => 'class'],
    'Created At' => ['key' => 'created_at'],
    'Status'     => [
        'key'   => 'status',
        'class' => 'status %s'
    ],
];

$db   = new Database();
$conn = $db->connect();

$page = getCurrentPage();
$totalPages = getTotalPages($conn, $db, "Student");
$result = getEventTable($conn, $db, "Student", $page);

$db->disconnect($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EduEventManager - Events</title>

    <link rel="stylesheet" href="styles/components/header.css">
    <link rel="stylesheet" href="styles/components/table.css">
    <link rel="stylesheet" href="styles/students.css">
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

</body>
</html>
