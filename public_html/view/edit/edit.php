<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . 'src/components/Forms/EditForm.php';
require_once PROJECT_ROOT . 'src/components/Header.php';
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../../styles/components/header.css">
    <link rel="stylesheet" href="edit.css">

    <title>EduEventManager</title>
</head>

<body>

    <?php Header::render("") ?>

    <main>

        <?php
        $table = $_GET['type'] ?? '';
        $id = intval($_GET['id'] ?? 0);

        $form = new EditForm($table, $id);
        $form->render("UpdateForm", "POST", "");
        ?>

    </main>

    <script src="../../js/api_update.js"></script>
</body>

</html>