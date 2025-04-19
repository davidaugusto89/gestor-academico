<?php

namespace Tests\Unit\Controller;

use App\Controller\AuthController;
use App\Core\HttpStatus;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\Service;
use App\Core\TokenManager;
use App\Utils\Response;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    public function testLoginComSucesso(): void
    {
        $service = $this->createMock(Service::class);
        $tokenManager = $this->createMock(TokenManager::class);
        $response = $this->createMock(Response::class);

        $usuario = new Entity('Maria', 'maria@email.com', 'senha123', 1, 'admin');

        $service->method('autenticar')->willReturn($usuario);
        $tokenManager->method('gerar')->willReturn('fake-token');

        $response->expects($this->once())
            ->method('json')
            ->with([
                'token' => 'fake-token',
                'usuario' => [
                    'id' => 1,
                    'nome' => 'Maria',
                    'email' => 'maria@email.com',
                    'papel' => 'admin'
                ]
            ], HttpStatus::OK);

        $controller = new AuthController($service, $tokenManager, $response);
        $controller->login([
            'email' => 'maria@email.com',
            'senha' => 'senha123'
        ]);
    }

    public function testMeRetornaDadosDoUsuario(): void
    {
        $usuario = new \App\Domain\Usuario\Entity('Fulano', 'fulano@email.com', 'senha123', 99, 'admin');

        $mockResponse = $this->createMock(\App\Utils\Response::class);
        $mockResponse->expects($this->once())
            ->method('json')
            ->with([
                'id'    => 99,
                'nome'  => 'Fulano',
                'email' => 'fulano@email.com',
                'papel' => 'admin'
            ]);

        $stubService = $this->createStub(\App\Domain\Usuario\Service::class);
        $stubToken = $this->createStub(\App\Core\TokenManager::class);

        $controller = new class($stubService, $stubToken, $mockResponse, $usuario) extends \App\Controller\AuthController {
            private \App\Domain\Usuario\Entity $usuarioFake;

            public function __construct($service, $tokenManager, $response, $usuario)
            {
                parent::__construct($service, $tokenManager, $response);
                $this->usuarioFake = $usuario;
            }

            protected function usuarioAutenticado(): \App\Domain\Usuario\Entity
            {
                return $this->usuarioFake;
            }
        };

        $controller->me();
    }
}
