<?php

namespace App\Utils;

class Paginador
{
    public static function extrairParametros(array $params): array
    {
        $page = $params['page'] <= 0 ? 1 : $params['page'];
        $itemsPerPage = $params['itemsPerPage'] ?? 10;

        $offset = ($page - 1) * $itemsPerPage;

        $return = [
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
            'offset' => $offset,
        ];

        if ($params) {
            foreach ($params as $key => $value) {
                if (!in_array($key, ['page', 'itemsPerPage', 'offset']) && $value !== null) {
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }

    public static function formatarResultado(array $dados, int $total): array
    {
        return [
            'data' => $dados,
            'total' => $total,
        ];
    }
}
