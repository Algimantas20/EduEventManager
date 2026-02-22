<?php

declare(strict_types=1);

require_once __DIR__ . "/Config.php";
require_once PROJECT_ROOT . "src/database.php";

class Operation
{

    private const ALLOWED_TABLES =
    [
        'participations',
        'events',
        'students'
    ];

    public static function delete(int $id, string $table): bool
    {
        self::validateId($id);
        self::validateTable($table);
        try {
            (new Database())->query(
                "DELETE FROM `$table` WHERE id = ?",
                [$id]
            );
        } catch (Exception $e) {
            throw new Exception("Failed to delete record: " . $e->getMessage());
        }

        return true;
    }

    public static function update(string $table, int $id, array $data): bool
    {
        self::validateId($id);
        self::validateTable($table);

        if (empty($data)) {
            throw new InvalidArgumentException("No data provided");
        }

        $setParts = [];
        $params   = [];

        foreach ($data as $column => $value) {
            $column = trim($column);

            if ($column === '') {
                continue;
            }

            $setParts[] = "`$column` = ?";
            $params[]   = $value;
        }

        if (empty($setParts)) {
            throw new InvalidArgumentException("No valid columns");
        }

        $params[] = $id;

        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE id = ?",
            $table,
            implode(', ', $setParts)
        );

        (new Database())->query($sql, $params);

        return true;
    }

    public static function create(string $table, array $data): bool
    {
        self::validateTable($table);

        if (isset($data['id'])) {
            throw new InvalidArgumentException("ID should not be provided for creation");
        }

        $data = array_filter($data, fn($v) => $v !== null && $v !== '');

        if (empty($data)) {
            throw new InvalidArgumentException("No data provided");
        }

        $columns = array_keys($data);
        $values  = array_values($data);

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

        $sql = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            $table,
            implode(', ', array_map(fn($col) => "`$col`", $columns)),
            $placeholders
        );

        (new Database())->query($sql, $values, true);

        return true;
    }

    private static function validateId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid ID");
        }
    }

    private static function validateTable(string $table): void
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new InvalidArgumentException("Invalid table");
        }
    }
}
