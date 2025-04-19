<?php

namespace Tests\Fake;

use App\Core\ControllerFactory;
use App\Controller\BaseController;

class ControllerFactoryStub extends ControllerFactory
{
    public function __construct()
    {
        // Chama um construtor vazio, pois o stub não precisa de dependências reais
    }

    public function make(string $controllerClass): object
    {
        return new FakeController();
    }
}
