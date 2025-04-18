<?php

namespace Tests\Unit\Controller;

use App\Controller\UsuarioController;
use App\Domain\Usuario\Service;
use App\Domain\Usuario\DTO;
use App\Domain\Usuario\Entity;
use App\Utils\Response;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários para o controller de usuários.
 */
class UsuarioControllerTest extends TestCase
{
    private UsuarioController $controller;
    private \PHPUnit\Framework\MockObject\MockObject $service;

    protected function setUp(): void
    {
        Response::ativarModoTeste();
        $this->service = $this->createMock(Service::class);
        $this->controller = new UsuarioController($this->service);
        ob_start();
    }

    protected function tearDown(): void
    {
        Response::desativarModoTeste();
        ob_end_clean();
    }

    public function testCriarUsuario(): void
    {
        $this->service->expects($this->once())->method('criar');
        $this->controller->criar([
            'nome' => 'João',
            'email' => 'joao@email.com',
            'senha' => 'Senha123!',
            'papel' => 'admin'
        ]);
        $saida = ob_get_clean();
        $this->assertStringContainsString('Usuário cadastrado com sucesso', $saida);
        ob_start();
    }

    public function testListarUsuarios(): void
    {
        $this->service->method('listarTodos')->willReturn([
            'data' => [['id' => 1]],
            'total' => 1
        ]);
        $this->controller->listar(['page' => 1]);
        $saida = ob_get_clean();
        $this->assertStringContainsString('"total": 1', $saida);
        ob_start();
    }

    public function testBuscarUsuarioPorId(): void
    {
        $usuario = $this->createMock(Entity::class);
        $usuario->method('jsonSerialize')->willReturn(['id' => 1, 'nome' => 'João']);
        $this->service->method('buscarPorId')->willReturn($usuario);
        $this->controller->buscar(1);
        $saida = ob_get_clean();
        $this->assertStringContainsString('"nome": "João"', $saida);
        ob_start();
    }

    public function testBuscarPorEmail(): void
    {
        $usuario = $this->createMock(Entity::class);
        $usuario->method('jsonSerialize')->willReturn(['email' => 'joao@email.com']);
        $this->service->method('buscarPorEmail')->willReturn($usuario);
        $this->controller->buscarPorEmail('joao@email.com');
        $saida = ob_get_clean();
        $this->assertStringContainsString('"email": "joao@email.com"', $saida);
        ob_start();
    }

    public function testAtualizarUsuario(): void
    {
        $this->service->expects($this->once())->method('atualizar');
        $this->controller->atualizar(1, [
            'nome' => 'João',
            'email' => 'joao@email.com',
            'senha' => 'Senha123!',
            'papel' => 'admin'
        ]);
        $saida = ob_get_clean();
        $this->assertStringContainsString('Usuário atualizado com sucesso', $saida);
        ob_start();
    }

    public function testRemoverUsuario(): void
    {
        $this->service->expects($this->once())->method('remover');
        $this->controller->remover(1);
        $saida = ob_get_clean();
        $this->assertStringContainsString('Usuário removido com sucesso', $saida);
        ob_start();
    }
}
