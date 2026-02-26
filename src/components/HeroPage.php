<?php

require_once '../../../src/Config.php';
require_once PROJECT_ROOT . "src/database.php";

class HeroPage
{
    public function render()
    {
        $students = $this->getRecordCount("students");
        $events = $this->getRecordCount("events");
        $participations = $this->getRecordCount("participations");

        echo "
        <div class='hero-container'>
            <div class='hero-card'>
                <h3>Total Events</h3>
                <p>{$events}</p>
            </div>

            <div class='hero-card'>
                <h3>Total Students</h3>
                <p>{$students}</p>
            </div>

            <div class='hero-card'>
                <h3>Total Participations</h3>
                <p>{$participations}</p>
            </div>
        </div>
        ";
    }

    private function getRecordCount(string $table): int
    {
        $allowedTables = ['students', 'events', 'participations'];

        if (!in_array($table, $allowedTables, true)) {
            return 0;
        }

        $db = new Database();

        $sql = "SELECT COUNT(*) AS total FROM {$table} WHERE status='A'";
        $result = $db->query($sql);

        if ($row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }

        return 0;
    }
}
