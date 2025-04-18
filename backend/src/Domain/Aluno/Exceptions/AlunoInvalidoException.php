<?php

namespace App\Domain\Aluno\Exceptions;

use App\Core\Exceptions\BadRequestException;

/**
 * Exceção lançada quando os dados de um aluno são inválidos.
 *
 * Usada na validação de campos como nome, email, CPF, senha e data de nascimento.
 */
class AlunoInvalidoException extends BadRequestException
{
    /**
     * @param string $mensagem Mensagem da exceção
     * @param int $codigo Código opcional
     */
    public function __construct(string $mensagem = "Aluno inválido.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
