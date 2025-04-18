<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AlunoController;
use App\Domain\Aluno\Service;
use App\Domain\Aluno\DTO;
use App\Utils\Response;
use App\Domain\Aluno\Entity;
use App\Core\HttpStatus;

class AlunoControllerTest extends TestCase
{
    private Service $service;
    private AlunoController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        Response::ativarModoTeste();
        $this->service = $this->createMock(Service::class);
        $this->controller = new AlunoController($this->service);
    }

    protected function tearDown(): void
    {
        Response::desativarModoTeste();
        parent::tearDown();
    }

    public function testCriar(): void
    {
        $dados = ['nome' => 'Maria', 'email' => 'maria@email.com', 'senha' => 'Senha123!', 'cpf' => '123.456.789-00', 'data_nascimento' => '01/01/2000'];

        $this->service
            ->expects($this->once())
            ->method('criar')
            ->with($this->isInstanceOf(DTO::class));

        ob_start();
        $this->controller->criar($dados);
        $output = ob_get_clean();

        $this->assertStringContainsString('Aluno cadastrado com sucesso.', $output);
        $this->assertEquals(201, \App\Utils\Response::getStatus());
    }

    public function testListar(): void
    {
        $params = ['page' => 1, 'itemsPerPage' => 10];
        $retornoSimulado = ['data' => [['nome' => 'João']], 'total' => 1];

        $this->service
            ->expects($this->once())
            ->method('listarTodos')
            ->willReturn($retornoSimulado);

        ob_start();
        $this->controller->listar($params);
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertStringContainsString('"total": 1', $output);
    }

    public function testBuscar(): void
    {
        $entidade = new Entity('Carlos', 'carlos@email.com', 'Senha123!', '12345678900', '2000-01-01', 5);

        $this->service
            ->expects($this->once())
            ->method('buscarPorId')
            ->with(5)
            ->willReturn($entidade);

        ob_start();
        $this->controller->buscar(5);
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertStringContainsString('"nome": "Carlos"', $output);
    }

    public function testBuscarPorNome(): void
    {
        $this->service
            ->expects($this->once())
            ->method('buscarPorNome')
            ->with('João')
            ->willReturn([['id' => 1, 'nome' => 'João']]);

        ob_start();
        $this->controller->buscarPorNome(['nome' => 'João']);
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertStringContainsString('"nome": "João"', $output);
    }

    public function testAtualizar(): void
    {
        $dados = ['nome' => 'Maria', 'email' => 'maria@email.com', 'senha' => 'Senha123!', 'cpf' => '123.456.789-00', 'data_nascimento' => '01/01/2000'];

        $this->service
            ->expects($this->once())
            ->method('atualizar')
            ->with(2, $this->isInstanceOf(DTO::class));

        ob_start();
        $this->controller->atualizar(2, $dados);
        $output = ob_get_clean();

        $this->assertStringContainsString('Aluno atualizado com sucesso.', $output);
    }

    public function testRemover(): void
    {
        $this->service
            ->expects($this->once())
            ->method('remover')
            ->with(7);

        ob_start();
        $this->controller->remover(7);
        $output = ob_get_clean();

        $this->assertStringContainsString('Aluno removido com sucesso.', $output);
    }
}
