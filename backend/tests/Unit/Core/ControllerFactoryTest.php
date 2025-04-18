<?php

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use App\Core\ControllerFactory;
use App\Controller\AlunoController;
use App\Controller\TurmaController;
use App\Controller\MatriculaController;
use App\Controller\AuthController;
use App\Controller\HealthController;
use App\Controller\UsuarioController;
use App\Controller\ExampleController;

class ControllerFactoryTest extends TestCase
{
    public function testExampleController()
    {
        $controller = ControllerFactory::make(ExampleController::class);
        $this->assertInstanceOf(ExampleController::class, $controller);
    }
}
