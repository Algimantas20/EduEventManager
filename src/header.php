<?php

global $pages;
$pages = [
    'Home'     => 'index.php',
    'Events'   => 'events.php',
    'Students' => 'students.php',
];

function renderPageHeader(string $currentPageTitle): void
{
    global $pages;

    echo '<header>';
    echo '<h1>EduEventManager - ' . $currentPageTitle . '</h1>';
    echo '<nav>';
    foreach ($pages as $title => $url) {
        $class = ($title === $currentPageTitle) ? ' class="active"' : '';
        echo '<a href="' . $url . '"' . $class . '>' . $title . '</a> ';
    }
    echo '</nav>';
    echo '</header>';
}

?>
