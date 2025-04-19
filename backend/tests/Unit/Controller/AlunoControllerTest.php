<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AlunoController;
use App\Domain\Aluno\Service;
use App\Domain\Aluno\DTO;
use App\Domain\Aluno\Entity;
use App\Utils\Response;
use App\Core\HttpStatus;

class AlunoControllerTest extends TestCase
{
    private Service $service;
    private Response $response;
    private AlunoController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->createMock(Service::class);
        $this->response = $this->createMock(Response::class);
        $this->controller = new AlunoController($this->service, $this->response);
    }

    public function testCriar(): void
    {
        $dados = [
            'nome' => 'Maria',
            'email' => 'maria@email.com',
            'senha' => 'Senha123!',
            'cpf' => '123.456.789-00',
            'data_nascimento' => '01/01/2000'
        ];

        $this->service
            ->expects($this->once())
            ->method('criar')
            ->with($this->isInstanceOf(DTO::class));

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Aluno cadastrado com sucesso.'], HttpStatus::CREATED);

        $this->controller->criar($dados);
    }

    public function testListar(): void
    {
        $params = ['page' => 1, 'itemsPerPage' => 10];
        $retornoSimulado = ['data' => [['nome' => 'João']], 'total' => 1];

        $this->service
            ->expects($this->once())
            ->method('listarTodos')
            ->willReturn($retornoSimulado);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(
                $this->callback(fn($resultado) => isset($resultado['total']) && $resultado['total'] === 1),
                HttpStatus::OK
            );

        $this->controller->listar($params);
    }

    public function testBuscar(): void
    {
        $entidade = new Entity('Carlos', 'carlos@email.com', 'Senha123!', '12345678900', '2000-01-01', 5);

        $this->service
            ->expects($this->once())
            ->method('buscarPorId')
            ->with(5)
            ->willReturn($entidade);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with($entidade, HttpStatus::OK);

        $this->controller->buscar(5);
    }

    public function testBuscarPorNome(): void
    {
        $resultado = [['id' => 1, 'nome' => 'João']];

        $this->service
            ->expects($this->once())
            ->method('buscarPorNome')
            ->with('João')
            ->willReturn($resultado);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with($resultado, HttpStatus::OK);

        $this->controller->buscarPorNome(['nome' => 'João']);
    }

    public function testAtualizar(): void
    {
        $dados = [
            'nome' => 'Maria',
            'email' => 'maria@email.com',
            'senha' => 'Senha123!',
            'cpf' => '123.456.789-00',
            'data_nascimento' => '01/01/2000'
        ];

        $this->service
            ->expects($this->once())
            ->method('atualizar')
            ->with(2, $this->isInstanceOf(DTO::class));

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Aluno atualizado com sucesso.'], HttpStatus::OK);

        $this->controller->atualizar(2, $dados);
    }

    public function testRemover(): void
    {
        $this->service
            ->expects($this->once())
            ->method('remover')
            ->with(7);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Aluno removido com sucesso.'], HttpStatus::OK);

        $this->controller->remover(7);
    }
}
