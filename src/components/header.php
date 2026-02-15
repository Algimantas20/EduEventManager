<?php
class Header
{
    public static function render(string $currentPageTitle): void
    {
        $basePath = '/~PII50461LA';

        $pages = [
            'Home'       => $basePath . '/home',
            'Events'     => $basePath . '/events',
            'Students'   => $basePath . '/students'
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