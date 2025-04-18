<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\DTO;

class TurmaDTOTest extends TestCase
{
    public function testCriaDTOComDadosValidos()
    {
        $dados = [
            'nome' => '  Backend PHP  ',
            'descricao' => '  Curso avançado de PHP '
        ];

        $dto = DTO::fromArray($dados);

        $this->assertInstanceOf(DTO::class, $dto);
        $this->assertSame('Backend PHP', $dto->getNome());
        $this->assertSame('Curso avançado de PHP', $dto->getDescricao());
    }

    public function testCriaDTOComCamposVazios()
    {
        $dto = DTO::fromArray([]);

        $this->assertSame('', $dto->getNome());
        $this->assertSame('', $dto->getDescricao());
    }
}
