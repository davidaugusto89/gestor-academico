<?php

namespace Tests\Unit\Controller;

use App\Controller\UsuarioController;
use App\Domain\Usuario\Service;
use App\Domain\Usuario\DTO;
use App\Domain\Usuario\Entity;
use App\Utils\Response;
use App\Core\HttpStatus;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários para o controller de usuários.
 */
class UsuarioControllerTest extends TestCase
{
    private UsuarioController $controller;
    private \PHPUnit\Framework\MockObject\MockObject $service;
    private \PHPUnit\Framework\MockObject\MockObject $response;

    protected function setUp(): void
    {
        $this->service = $this->createMock(Service::class);
        $this->response = $this->createMock(Response::class);
        $this->controller = new UsuarioController($this->service, $this->response);
    }

    public function testCriarUsuario(): void
    {
        $dados = [
            'nome' => 'João',
            'email' => 'joao@email.com',
            'senha' => 'Senha123!',
            'papel' => 'admin'
        ];

        $this->service->expects($this->once())->method('criar');
        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Usuário cadastrado com sucesso.'], HttpStatus::CREATED);

        $this->controller->criar($dados);
    }

    public function testListarUsuarios(): void
    {
        $retorno = ['data' => [['id' => 1]], 'total' => 1];

        $this->service
            ->method('listarTodos')
            ->willReturn($retorno);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(
                $this->callback(fn($res) => $res['total'] === 1 && isset($res['data'][0]['id'])),
                HttpStatus::OK
            );

        $this->controller->listar(['page' => 1]);
    }

    public function testBuscarUsuarioPorId(): void
    {
        $usuario = $this->createMock(Entity::class);

        $this->service
            ->method('buscarPorId')
            ->with(1)
            ->willReturn($usuario);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with($usuario, HttpStatus::OK);

        $this->controller->buscar(1);
    }

    public function testBuscarPorEmail(): void
    {
        $usuario = $this->createMock(Entity::class);

        $this->service
            ->method('buscarPorEmail')
            ->with('joao@email.com')
            ->willReturn($usuario);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with($usuario, HttpStatus::OK);

        $this->controller->buscarPorEmail('joao@email.com');
    }

    public function testAtualizarUsuario(): void
    {
        $dados = [
            'nome' => 'João',
            'email' => 'joao@email.com',
            'senha' => 'Senha123!',
            'papel' => 'admin'
        ];

        $this->service
            ->expects($this->once())
            ->method('atualizar')
            ->with(1, $this->isInstanceOf(DTO::class));

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Usuário atualizado com sucesso.'], HttpStatus::OK);

        $this->controller->atualizar(1, $dados);
    }

    public function testRemoverUsuario(): void
    {
        $this->service
            ->expects($this->once())
            ->method('remover')
            ->with(1);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Usuário removido com sucesso.'], HttpStatus::OK);

        $this->controller->remover(1);
    }
}
