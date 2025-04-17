<?php

namespace App\Utils;

class Normalizer
{
    public static function cpf(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf);
    }

    public static function data(string $data): string
    {
        // Converte de dd/mm/yyyy para yyyy-mm-dd
        $partes = explode('/', $data);
        if (count($partes) === 3) {
            return "{$partes[2]}-{$partes[1]}-{$partes[0]}";
        }

        return $data;
    }
}
