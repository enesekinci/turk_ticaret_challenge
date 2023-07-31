<?php

if (!function_exists('dd')) {
    function dd()
    {
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
