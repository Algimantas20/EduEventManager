<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

define('RECORDS_PER_PAGE', 10);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/database.php';
require_once PROJECT_ROOT . 'src/components/Tables/DatabaseTable.php';
require_once PROJECT_ROOT . 'src/components/Header.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/header.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/table.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>view/participations/participations.css">

    <title>EduEventManager - Participations</title>
</head>

<body>

    <?= Header::render("Participations") ?>

    <main class="participations-page">

        <?php
        $table = new DatabaseTable("participations");
        $table->render(Config::PARTICIPATION_FIELDS, "participation-table");
        ?>

        <section class="table-footer">
            <section class="record-count">
                Total Participations: <?php echo $table->getTotalRecordCount() ?>
            </section>

            <?php $table->showReports() ?>

            <a class="btn add-btn" href="<?= Config::$BASE_URL ?>view/add/add.php?type=participations">Add Participation</a>
        </section>

    </main>

    <script src="js/app.js"></script>
    <script src="js/api_delete.js"></script>

</body>

</html>