<?php

namespace App\Utils;

/**
 * Classe utilitária para normalização de dados de entrada.
 *
 * @package App\Utils
 */
class Normalizer
{
    /**
     * Remove todos os caracteres não numéricos de um CPF.
     *
     * @param string $cpf CPF com ou sem máscara.
     * @return string CPF apenas com números.
     */
    public static function cpf(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf);
    }

    /**
     * Converte data do formato dd/mm/yyyy para yyyy-mm-dd.
     *
     * @param string $data Data em formato brasileiro.
     * @return string Data em formato ISO ou string original se inválida.
     */
    public static function data(string $data): string
    {
        $partes = explode('/', $data);
        if (count($partes) === 3) {
            return "{$partes[2]}-{$partes[1]}-{$partes[0]}";
        }

        return $data;
    }
}
