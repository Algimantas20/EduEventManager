<html>
<head>
    <title>EduEventManager</title>
    <link rel="stylesheet" href="styles/components/header.css">
    <link rel="stylesheet" href="styles/components/table.css">
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <header>
        <h1>EduEventManager</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="#">Events</a>
        </nav>
    </header>

    <main>
    <main>
</body>
</html>

<?php
require_once '../src/database.php';

$db = new Database();
$conn = $db->connect();

$recordsPerPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;


$countSql = "SELECT COUNT(*) AS total FROM Event";
$countResult = $db->query($conn, $countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $recordsPerPage);

$sql = "SELECT * FROM Event ORDER BY created_at DESC LIMIT $recordsPerPage OFFSET $offset";
$result = $db->query($conn, $sql);

if ($result && $result->num_rows > 0) {

    echo "<div class='table-container'>";
    echo "<table class='event-table'>";
    echo "<thead>
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
          </thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['event_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['event_type']}</td>
                <td>{$row['location']}</td>
                <td>{$row['event_date']}</td>
                <td>{$row['created_at']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

    echo "<div class='pagination'>";

    if ($page > 1) {
        echo "<a href='?page=" . ($page - 1) . "'>&laquo; Prev</a>";
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i === $page ? "active" : "";
        echo "<a class='$active' href='?page=$i'>$i</a>";
    }

    if ($page < $totalPages) {
        echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
    }

    echo "</div>";

} else {
    echo "No events found.";
}

$db->disconnect($conn);
?>

