<?php
require_once __DIR__ . '/../database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

function IsValidId(int $id) : bool
{
    return $id > 0;
}

function UpdateRecord(string $tableName, int $id, array $data) : void
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

    $conn = (new Database())->connect();

    $columns = [];
    $values = [];
    $types = "";

    foreach ($data as $key => $value)
    {
        $key = trim($key);
        if ($key === '') continue;
        $columns[] = "`$key` = ?";
        $values[] = $value;
        $types .= "s";
    }

    if (empty($columns))
    {
        throw new Exception("No valid columns to update");
    }

    $sql = "UPDATE `$tableName` SET " . implode(", ", $columns) . " WHERE id = ?";
    $values[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    if (!$stmt)
    {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$values);
    $stmt->execute();

    if ($stmt->error)
    {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
