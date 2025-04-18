<?php

namespace App\Domain\Matricula\Exceptions;

use App\Core\Exceptions\BadRequestException;

/**
 * Exceção lançada quando os dados da matrícula são inválidos.
 *
 * Utilizada para sinalizar erros como IDs ausentes ou malformados.
 */
class MatriculaInvalidaException extends BadRequestException
{
    /**
     * @param string $mensagem Mensagem da exceção
     * @param int $codigo Código da exceção (opcional)
     */
    public function __construct(string $mensagem = "Matrícula inválida.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
