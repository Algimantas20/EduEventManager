<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../src/components/header.php';
include '../src/database.php';


$table = $_POST['table'] ?? '';
$id = intval($_POST['id'] ?? 0);

if (!$table || !$id) 
{
    die("Missing table or id");
}

$db = new Database();
$conn = $db->connect();

$sql = "SELECT * FROM `$table` WHERE id = $id LIMIT 1";
$result = $db->query($conn, $sql);

$row = $result->fetch_assoc();

if (!$row) 
{
    die("No record found");
}

function DisplayStatusInput(string $currentValue) : void
{
    $options =
    [
        'A' => 'Active',
        'I' => 'Inactive',
        'D' => 'Deleted'
    ];

    echo "<select name='status'>";

    foreach ($options as $value => $label)
    {
        $selected = ($value === $currentValue) ? " selected" : "";
        echo "<option value='$value'$selected>$label</option>";
    }

    echo "</select>";
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="styles/components/header.css">
    <link rel="stylesheet" href="styles/edit.css">

    <title>EduEventManager</title>
</head>
<body>

<?= renderPageHeader('') ?>

<main>

<form id="updateForm" method="POST" action="api/api_update.php">
<?php foreach ($row as $column => $value):
    $columnSafe = htmlspecialchars($column, ENT_QUOTES, 'UTF-8');
    $valueSafe  = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

    switch ($column):
        case 'id': ?>
            <input type="hidden" name="id" value="<?= $valueSafe ?>">
            <input type="hidden" name="table" value="<?= htmlspecialchars($table, ENT_QUOTES, 'UTF-8') ?>">
        <?php break;

        case 'status': ?>
            <label><?= ucfirst($columnSafe) ?>
                <?php DisplayStatusInput($valueSafe); ?>
            </label>
        <?php break;

        default: ?>
            <label><?= ucfirst($columnSafe) ?>
                <input type="text" name="<?= $columnSafe ?>" value="<?= $valueSafe ?>">
            </label>
    <?php endswitch;
endforeach; ?>
<button type="submit">Save</button>
</form>


</main>

<script src="js/app.js"></script>
</body>
</html>
