<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TurmaController;
use App\Domain\Turma\Service;
use App\Domain\Turma\DTO;
use App\Utils\Response;

/**
 * Testes unitários para o controller de Turma.
 */
class TurmaControllerTest extends TestCase
{
    private Service $service;
    private TurmaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->createMock(Service::class);
        $this->controller = new TurmaController($this->service);
        Response::ativarModoTeste();
        ob_start();
    }

    protected function tearDown(): void
    {
        ob_end_clean();
        Response::desativarModoTeste();
    }

    public function testCriarTurmaRetornaMensagemDeSucesso(): void
    {
        $this->service->expects($this->once())->method('criar');
        $this->controller->criar(['nome' => 'PHP', 'descricao' => 'Avançado']);
        $saida = ob_get_clean();
        $this->assertStringContainsString('Turma cadastrada com sucesso.', $saida);
        ob_start();
    }

    public function testListarTurmasRetornaListaComTotal(): void
    {
        $this->service
            ->method('listarTodos')
            ->willReturn([
                'data' => [['id' => 1, 'nome' => 'PHP']],
                'total' => 1
            ]);

        $this->controller->listar([]);
        $saida = ob_get_clean();
        $this->assertStringContainsString('PHP', $saida);
        $this->assertStringContainsString('"total": 1', $saida);
        ob_start();
    }

    public function testBuscarTurmaPorIdRetornaTurma(): void
    {
        $turmaMock = $this->createMock(\App\Domain\Turma\Entity::class);
        $turmaMock->method('jsonSerialize')->willReturn([
            'id' => 1,
            'nome' => 'PHP'
        ]);

        $this->service
            ->method('buscarPorId')
            ->willReturn($turmaMock);

        $this->controller->buscar(1);
        $saida = ob_get_clean();
        $this->assertStringContainsString('"nome": "PHP"', $saida);
        ob_start();
    }

    public function testBuscarPorNomeRetornaLista(): void
    {
        $this->service
            ->method('buscarPorNome')
            ->willReturn([['id' => 1, 'nome' => 'PHP']]);

        $this->controller->buscarPorNome('PHP');
        $saida = ob_get_clean();
        $this->assertStringContainsString('"nome": "PHP"', $saida);
        ob_start();
    }

    public function testAtualizarTurmaRetornaMensagem(): void
    {
        $this->service->expects($this->once())->method('atualizar');
        $this->controller->atualizar(1, ['nome' => 'PHP']);
        $saida = ob_get_clean();
        $this->assertStringContainsString('Turma atualizada com sucesso.', $saida);
        ob_start();
    }

    public function testRemoverTurmaRetornaMensagem(): void
    {
        $this->service->expects($this->once())->method('remover');
        $this->controller->remover(1);
        $saida = ob_get_clean();
        $this->assertStringContainsString('Turma removida com sucesso.', $saida);
        ob_start();
    }
}
