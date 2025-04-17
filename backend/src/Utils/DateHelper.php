<?php

namespace App\Utils;

class DateHelper
{
    public static function paraFormatoBanco(string $data): string
    {
        $dt = \DateTime::createFromFormat('d/m/Y', $data);
        return $dt ? $dt->format('Y-m-d') : $data;
    }
}
