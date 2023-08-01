<?php

$helpers = glob(__DIR__ . '/*_helper.php');

foreach ($helpers as $helper) {
    require_once $helper;
}
