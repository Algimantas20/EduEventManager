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
        <section class='hero'>
            <div class='hero-content'>
                <h1 class='hero-title'>EduEventManager</h1>
                <p class='hero-subtitle'>Simple. Structured. Efficient.</p>

                <div class='hero-container'>
                    <div class='hero-card'>
                        <h3>Total Events</h3>
                        <p>{$events}</p>
                        <a href='events' class='hero-btn'>View Events</a>
                    </div>

                    <div class='hero-card'>
                        <h3>Total Students</h3>
                        <p>{$students}</p>
                        <a href='students' class='hero-btn'>View Students</a>
                    </div>

                    <div class='hero-card'>
                        <h3>Total Participations</h3>
                        <p>{$participations}</p>
                        <a href='participations' class='hero-btn'>View Participations</a>
                    </div>
                </div>
            </div>
        </section>
        ";
    }

    private function getRecordCount(string $table): int
    {
        $allowedTables = ['students', 'events', 'participations'];

        if (!in_array($table, $allowedTables, true)) {
            return 0;
        }

        $db = new Database();

        $sql = "SELECT COUNT(*) AS total FROM {$table}";
        $result = $db->query($sql);

        if ($row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }

        return 0;
    }
}
