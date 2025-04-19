<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MatriculaController;
use App\Domain\Matricula\Service;
use App\Domain\Matricula\DTO;
use App\Utils\Response;
use App\Core\HttpStatus;

class MatriculaControllerTest extends TestCase
{
    private Service $serviceMock;
    private Response $responseMock;
    private MatriculaController $controller;

    protected function setUp(): void
    {
        $this->serviceMock = $this->createMock(Service::class);
        $this->responseMock = $this->createMock(Response::class);
        $this->controller = new MatriculaController($this->serviceMock, $this->responseMock);
    }

    public function testMatricular(): void
    {
        $dados = ['aluno_id' => 1, 'turma_id' => 2];

        $this->serviceMock
            ->expects($this->once())
            ->method('matricular')
            ->with($this->isInstanceOf(DTO::class));

        $this->responseMock
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Matrícula realizada com sucesso.'], HttpStatus::CREATED);

        $this->controller->matricular($dados);
    }

    public function testListar(): void
    {
        $dados = ['page' => 1, 'itemsPerPage' => 1];
        $esperado = ['data' => [['aluno_id' => 1]], 'total' => 1];

        $this->serviceMock
            ->method('listarTodos')
            ->willReturn($esperado);

        $this->responseMock
            ->expects($this->once())
            ->method('json')
            ->with(
                $this->callback(fn($res) => $res['data'] === $esperado['data'] && $res['total'] === 1),
                HttpStatus::OK
            );

        $this->controller->listar($dados);
    }

    public function testListarPorTurma(): void
    {
        $turmaId = 10;
        $esperado = [['aluno_id' => 1, 'nome' => 'Carlos']];

        $this->serviceMock
            ->expects($this->once())
            ->method('listarPorTurma')
            ->with($turmaId)
            ->willReturn($esperado);

        $this->responseMock
            ->expects($this->once())
            ->method('json')
            ->with($esperado, HttpStatus::OK);

        $this->controller->listarPorTurma($turmaId);
    }

    public function testRemover(): void
    {
        $dados = ['aluno_id' => 1, 'turma_id' => 2];

        $this->serviceMock
            ->expects($this->once())
            ->method('remover')
            ->with($this->isInstanceOf(DTO::class));

        $this->responseMock
            ->expects($this->once())
            ->method('json')
            ->with(['mensagem' => 'Matrícula removida com sucesso.'], HttpStatus::OK);

        $this->controller->remover($dados);
    }
}
