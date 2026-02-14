<?php
define('RECORDS_PER_PAGE', 10);

require_once '../../../src/utility.php';
require_once PROJECT_ROOT . 'src/database.php';
require_once PROJECT_ROOT . 'src/components/Table.php';
require_once PROJECT_ROOT . 'src/components/Header.php';

$table = new Table("Student");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../../styles/components/header.css">
    <link rel="stylesheet" href="../../styles/components/table.css">
    <link rel="stylesheet" href="students.css">

    <title>EduEventManager - Students</title>
</head>
<body>

<?= Header::render("Students") ?>

<main class="events-page">

<?= $table->render($student_fields, "student-table"); ?>

</main>

<script src="js/app.js"></script>

</body>
</html>
