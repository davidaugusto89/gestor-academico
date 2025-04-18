<?php

namespace App\Core;

class ControllerFactory
{
    public static function make(string $class)
    {
        return new $class();
    }
}
