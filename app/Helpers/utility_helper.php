<?php

if (!function_exists('dd')) {
    function dd()
    {
        echo "<style>body{background-color: #000;color: #fff;}</style>";

        $args = func_get_args();

        echo '<pre>';

        foreach ($args as $arg) {
            var_dump($arg);
            echo '<br/>';
            echo '<hr/>';
        }

        echo '</pre>';

        exit;
    }
}
