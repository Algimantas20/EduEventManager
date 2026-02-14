<?php
class Header
{
    public static function render(string $currentPageTitle): void
    {
        $basePath = '/~PII50461LA/view';

        $pages = [
            'Home'       => $basePath . '../',
            'Events'     => $basePath . '/events/view.php',
            'Students'   => $basePath . '/students/view.php'
        ];

        echo '<header>';
        echo '<h1>EduEventManager</h1>';
        echo '<nav>';

        foreach ($pages as $title => $url) 
        {
            $class = ($title === $currentPageTitle) ? ' class="active"' : '';
            echo '<a href="' . $url . '"' . $class . '>' . $title . '</a> ';
        }

        echo '</nav>';
        echo '</header>';
    }
}
?>