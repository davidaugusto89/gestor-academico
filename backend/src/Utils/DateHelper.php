<?php

namespace App\Utils;

/**
 * Classe utilitária para manipulação de datas.
 *
 * @package App\Utils
 */
class DateHelper
{
    /**
     * Converte uma data no formato brasileiro (d/m/Y) para o formato do banco (Y-m-d).
     *
     * @param string $data Data no formato brasileiro (ex: 31/12/2024).
     * @return string Data no formato do banco (Y-m-d), ou a string original se inválida.
     */
    public static function paraFormatoBanco(string $data): string
    {
        $dt = \DateTime::createFromFormat('d/m/Y', $data);
        return $dt ? $dt->format('Y-m-d') : $data;
    }
}
