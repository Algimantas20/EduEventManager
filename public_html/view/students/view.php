<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

define('RECORDS_PER_PAGE', 10);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/database.php';
require_once PROJECT_ROOT . 'src/components/Table.php';
require_once PROJECT_ROOT . 'src/components/Header.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/header.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/table.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>view/students/students.css">

    <title>EduEventManager - Students</title>
</head>

<body>

    <?= Header::render("Students") ?>

    <main class="events-page">

        <?php
        $table = new Table("students");
        $table->render($student_fields, "student-table");
        ?>

        <section class="table-footer">
            <section class="record-count">
                Total Students: <?php echo $table->getTotalRecordCount() ?>
            </section>

            <a class="btn add-btn" href="<?= Config::$BASE_URL ?>view/add/add.php?type=students">Add Student</a>
        </section>

    </main>

    <script src="js/app.js"></script>
    <script src="js/api_delete.js"></script>

</body>

</html>