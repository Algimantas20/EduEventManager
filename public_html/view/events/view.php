<?php
define('RECORDS_PER_PAGE', 10);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/components/Header.php';
require_once PROJECT_ROOT . 'src/components/Table.php';

$table = new Table("Event");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>/styles/components/header.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>/styles/components/table.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>/events.css">

    <title>EduEventManager - Events</title>
</head>
<body>

<?= Header::render("Events") ?>

<main class="events-page">

    <?= $table->render($event_fields, "event-table"); ?>

</main>

<script src="js/app.js"></script>

</body>
</html>
