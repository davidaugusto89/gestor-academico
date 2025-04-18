<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MatriculaController;
use App\Domain\Matricula\Service;
use App\Domain\Matricula\DTO;
use App\Utils\Response;

class MatriculaControllerTest extends TestCase
{
    private $serviceMock;

    protected function setUp(): void
    {
        $this->serviceMock = $this->createMock(Service::class);
        Response::ativarModoTeste();
        ob_start();
    }

    protected function tearDown(): void
    {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        Response::desativarModoTeste();
    }

    public function testMatricular(): void
    {
        $dados = ['aluno_id' => 1, 'turma_id' => 2];
        $this->serviceMock->expects($this->once())
            ->method('matricular')
            ->with($this->isInstanceOf(DTO::class));

        $controller = new MatriculaController($this->serviceMock);
        $controller->matricular($dados);

        $saida = json_decode(ob_get_contents(), true);
        $this->assertEquals('Matrícula realizada com sucesso.', $saida['mensagem']);
    }

    public function testListar(): void
    {
        $dados = ['page' => 1, 'itemsPerPage' => 1];
        $esperado = ['data' => [['aluno_id' => 1]], 'total' => 1];

        $this->serviceMock->method('listarTodos')->willReturn($esperado);

        $controller = new MatriculaController($this->serviceMock);
        $controller->listar($dados);

        $saida = json_decode(ob_get_contents(), true);
        $this->assertEquals($esperado['data'], $saida['data']);
        $this->assertEquals($esperado['total'], $saida['total']);
    }

    public function testListarPorTurma(): void
    {
        $turmaId = 10;
        $esperado = [['aluno_id' => 1, 'nome' => 'Carlos']];
        $this->serviceMock->method('listarPorTurma')->with($turmaId)->willReturn($esperado);

        $controller = new MatriculaController($this->serviceMock);
        $controller->listarPorTurma($turmaId);

        $saida = json_decode(ob_get_contents(), true);
        $this->assertEquals($esperado, $saida);
    }

    public function testRemover(): void
    {
        $dados = ['aluno_id' => 1, 'turma_id' => 2];
        $this->serviceMock->expects($this->once())
            ->method('remover')
            ->with($this->isInstanceOf(DTO::class));

        $controller = new MatriculaController($this->serviceMock);
        $controller->remover($dados);

        $saida = json_decode(ob_get_contents(), true);
        $this->assertEquals('Matrícula removida com sucesso.', $saida['mensagem']);
    }
}
