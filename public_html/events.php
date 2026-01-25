<html>
<head>
    <title>EduEventManager</title>
    <link rel="stylesheet" href="styles/components/header.css">
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <header>
        <h1>EduEventManager</h1>
        <nav>
            <a href="#home">Home</a>
            <a href="#events">Events</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
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

$sql = "SELECT * FROM Event";
$result = $db->query($conn, $sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr>
            <th>ID</th>
            <th>Event ID</th>
            <th>Name</th>
            <th>Event Type</th>
            <th>Location</th>
            <th>Event Date</th>
            <th>Created At</th>
            <th>Status</th>
        </tr>";

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

    echo "</table>";
} else {
    echo "No events found.";
}

$db->disconnect($conn);
?>
