<?php
require_once __DIR__ . '/../database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

function IsValidId(int $id): bool
{
    return $id > 0;
}

function UpdateRecord(string $tableName, int $id, array $data): void
{
    if (!IsValidId($id))
    {
        throw new Exception("Invalid ID");
    }

    $allowedTables = ['Participation', 'Event', 'Student'];
    if (!in_array($tableName, $allowedTables, true))
    {
        throw new Exception("Invalid table");
    }

    if (empty($data))
    {
        throw new Exception("No data provided to update");
    }

    $columns = [];
    $params  = [];

    foreach ($data as $key => $value)
    {
        $key = trim($key);
        if ($key === '') continue;

        $columns[] = "`$key` = ?";
        $params[]  = $value;
    }

    if (empty($columns))
    {
        throw new Exception("No valid columns to update");
    }

    $sql = sprintf(
        "UPDATE `%s` SET %s WHERE id = ?",
        $tableName,
        implode(", ", $columns)
    );

    $params[] = $id;

    $db = new Database();

    $db->query($sql, $params);

    $db->disconnect();
}
?>
