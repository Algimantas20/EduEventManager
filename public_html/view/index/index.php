<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/components/Header.php'; 
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>/styles/components/header.css">
    <link rel="stylesheet" href="<?= Config::$BASE_URL ?>/index.css">

    <title>EduEventManager</title>
</head>
<body>
    <?= Header::render("Home") ?>

    <main>

    </main>
</body>
</html>
