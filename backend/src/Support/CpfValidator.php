<?php

namespace App\Support;

/**
 * Classe responsável por validar números de CPF.
 *
 * @package App\Support
 */
class CpfValidator
{
    /**
     * Valida um CPF verificando se ele tem o formato correto e dígitos verificadores válidos.
     *
     * @param string $cpf CPF a ser validado.
     * @return bool Retorna true se o CPF for válido, caso contrário, false.
     */
    public static function isValido(string $cpf): bool
    {
        // Remove tudo que não for número
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula os dois dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += (int) $cpf[$i] * ($t + 1 - $i);
            }

            $digito = (10 * $soma) % 11 % 10;
            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }
}
