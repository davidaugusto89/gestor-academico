<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TurmaController;
use App\Domain\Turma\Service;
use App\Domain\Turma\DTO;
use App\Utils\Response;
use App\Core\HttpStatus;

/**
 * Testes unitários para o controller de Turma.
 */
class TurmaControllerTest extends TestCase
{
    private Service $service;
    private Response $response;
    private TurmaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->createMock(Service::class);
        $this->response = $this->createMock(Response::class);
        $this->controller = new TurmaController($this->service, $this->response);
    }

    public function testCriarTurmaRetornaMensagemDeSucesso(): void
    {
        $this->service
            ->expects($this->once())
            ->method('criar');

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Turma cadastrada com sucesso.'], HttpStatus::CREATED);

        $this->controller->criar(['nome' => 'PHP', 'descricao' => 'Avançado']);
    }

    public function testListarTurmasRetornaListaComTotal(): void
    {
        $esperado = [
            'data' => [['id' => 1, 'nome' => 'PHP']],
            'total' => 1
        ];

        $this->service
            ->method('listarTodos')
            ->willReturn($esperado);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(
                $this->callback(fn($res) => $res['data'][0]['nome'] === 'PHP' && $res['total'] === 1),
                HttpStatus::OK
            );

        $this->controller->listar([]);
    }

    public function testBuscarTurmaPorIdRetornaTurma(): void
    {
        $turmaMock = $this->createMock(\App\Domain\Turma\Entity::class);

        $this->service
            ->method('buscarPorId')
            ->with(1)
            ->willReturn($turmaMock);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with($turmaMock, HttpStatus::OK);

        $this->controller->buscar(1);
    }

    public function testBuscarPorNomeRetornaLista(): void
    {
        $resultado = [['id' => 1, 'nome' => 'PHP']];

        $this->service
            ->method('buscarPorNome')
            ->with('PHP')
            ->willReturn($resultado);

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with($resultado, HttpStatus::OK);

        $this->controller->buscarPorNome('PHP');
    }

    public function testAtualizarTurmaRetornaMensagem(): void
    {
        $this->service
            ->expects($this->once())
            ->method('atualizar');

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Turma atualizada com sucesso.'], HttpStatus::OK);

        $this->controller->atualizar(1, ['nome' => 'PHP']);
    }

    public function testRemoverTurmaRetornaMensagem(): void
    {
        $this->service
            ->expects($this->once())
            ->method('remover');

        $this->response
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Turma removida com sucesso.'], HttpStatus::OK);

        $this->controller->remover(1);
    }
}
