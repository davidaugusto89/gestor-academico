<?php

namespace App\Domain\Usuario;

/**
 * Classe responsável por definir os campos permitidos para filtros de Usuário.
 *
 * @package App\Domain\Usuario
 */
class Filter
{
    /**
     * Retorna os campos permitidos para filtragem de usuários.
     *
     * @return array
     */
    public static function camposPermitidos(): array
    {
        return ['id', 'nome'];
    }
}
