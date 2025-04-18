<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\Validator;
use App\Domain\Turma\DTO;
use App\Domain\Turma\Exceptions\TurmaInvalidaException;

class TurmaValidatorTest extends TestCase
{
    public function testValidaTurmaComDadosValidos()
    {
        $dto = DTO::fromArray([
            'nome' => 'Turma A',
            'descricao' => 'Turma de backend avançado'
        ]);

        Validator::validar($dto);
        $this->assertTrue(true);
    }

    public function testNaoValidaNomeMenorQueTresCaracteres()
    {
        $this->expectException(TurmaInvalidaException::class);
        $this->expectExceptionMessage('O nome da turma deve ter no mínimo 3 caracteres.');

        $dto = DTO::fromArray([
            'nome' => 'AB',
            'descricao' => 'Descrição válida'
        ]);

        Validator::validar($dto);
    }

    public function testNaoValidaDescricaoVazia()
    {
        $this->expectException(TurmaInvalidaException::class);
        $this->expectExceptionMessage('A descrição da turma é obrigatória.');

        $dto = DTO::fromArray([
            'nome' => 'Turma B',
            'descricao' => ''
        ]);

        Validator::validar($dto);
    }
}
