<?php

namespace App\Domain\Aluno\Exceptions;

use App\Core\Exceptions\BadRequestException;

/**
 * Exceção lançada quando um aluno com o mesmo e-mail ou CPF já está cadastrado.
 *
 * Usada na verificação de duplicidade no repositório durante criação ou atualização.
 */
class AlunoJaExisteException extends BadRequestException
{
    /**
     * @param string $mensagem Mensagem da exceção
     * @param int $codigo Código opcional
     */
    public function __construct(string $mensagem = "Aluno já cadastrado com esse email ou CPF.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
