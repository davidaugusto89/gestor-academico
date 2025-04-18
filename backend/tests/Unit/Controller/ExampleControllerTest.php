<?php

namespace App\Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ExampleController;

class ExampleControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        // Limpa o estado após cada teste
        ExampleController::$executadoCom = '';
    }

    public function testShow()
    {
        $controller = new ExampleController();
        $controller->show(10, ['busca' => 'abc']);

        $this->assertEquals(
            'show: 10 / query: {"busca":"abc"}',
            ExampleController::$executadoCom
        );
    }

    public function testStore()
    {
        $controller = new ExampleController();
        $controller->store(['nome' => 'Maria']);

        $this->assertEquals(
            'store: {"nome":"Maria"}',
            ExampleController::$executadoCom
        );
    }

    public function testUpdate()
    {
        $controller = new ExampleController();
        $controller->update(7, ['ativo' => false]);

        $this->assertEquals(
            'update: 7 / data: {"ativo":false}',
            ExampleController::$executadoCom
        );
    }

    public function testDestroy()
    {
        $controller = new ExampleController();
        $controller->destroy(99);

        $this->assertEquals(
            'destroy: 99',
            ExampleController::$executadoCom
        );
    }
}
