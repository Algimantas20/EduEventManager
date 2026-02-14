<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../src/Config.php';
require_once PROJECT_ROOT . 'src/components/input.php';
require_once PROJECT_ROOT . 'src/components/Header.php';
require_once PROJECT_ROOT . 'src/database.php';


$table = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);

if (!$table || !$id) 
{
    die("Missing table or id");
}

$db = new Database();

$result = $db->query("SELECT * FROM `$table` WHERE id = $id LIMIT 1");

$row = $result->fetch_assoc();

if (!$row) 
{
    die("No record found");
}

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

<?= Header::render("") ?>

<main>

<form id="updateForm" method="POST" action="api/api_update.php">
<?php foreach ($row as $column => $value):
    $columnSafe = h($column);
    $valueSafe  = h($value);

    switch ($column):
        case 'id': ?>
            <input type="hidden" name="id" value="<?= h($valueSafe) ?>">
            <input type="hidden" name="table" value="<?= h($table) ?>">
        <?php break;

        case 'status': ?>
            <label><?= ucfirst($columnSafe) ?>
                <?php DisplayStatusInput($valueSafe); ?>
            </label>
        <?php break;

        default: ?>
            <label><?= ucfirst($columnSafe) ?>
                <input type="text" name="<?= h($columnSafe) ?>" value="<?= h($valueSafe) ?>">
            </label>
    <?php endswitch;
endforeach; ?>
<button type="submit">Save</button>
</form>

</main>

<script src="js/app.js"></script>
</body>
</html>
