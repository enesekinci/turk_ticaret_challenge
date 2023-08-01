<?php

namespace App\Controllers;

abstract class Controller
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        // do nothing
    }
}
