<?php

namespace App\Controller;

class FakeController
{
    public static array $registro = [];

    public function index(array $params = [])
    {
        self::$registro = ['chamado' => true, 'params' => $params];
    }
}
