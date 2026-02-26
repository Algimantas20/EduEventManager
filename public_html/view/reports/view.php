<?php
require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/components/Header.php';
require_once PROJECT_ROOT . 'src/components/Report.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/header.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>styles/components/table.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>view/reports/reports.css">
</head>

<body>
    <?= Header::render("Reports") ?>

    <main>
        <?php
        $report = new Report($_GET['type'] ?? '', $_GET['id'] ?? null);
        $report->render();
        ?>
    </main>

    <script src="<?= Config::$BASE_URL ?>view/reports/reports.js"></script>
</body>

</html>