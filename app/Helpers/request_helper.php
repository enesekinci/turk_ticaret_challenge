<?php

if (!function_exists('request')) {
    function request()
    {
        return \Core\Request::getInstance();
    }
}
