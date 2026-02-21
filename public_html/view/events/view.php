<?php
define('RECORDS_PER_PAGE', 10);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/components/Header.php';
require_once PROJECT_ROOT . 'src/components/Table.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/header.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/table.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>view/events/events.css">

    <title>EduEventManager - Events</title>
</head>

<body>

    <?= Header::render("Events") ?>

    <main class="events-page">

        <?php
        $table = new Table("events");
        $table->render($event_fields, "event-table");
        ?>

        <section class="table-footer">
            <section class="record-count">
                Total Events: <?php echo $table->getTotalRecordCount() ?>
            </section>

            <a class=" btn add-btn" href="<?= Config::$BASE_URL ?>view/add/add.php?type=events">Add Event</a>
        </section>
    </main>

    <script src="js/app.js"></script>
    <script src="js/api_delete.js"></script>

</body>

</html>