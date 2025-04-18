<?php

namespace App\Utils;

/**
 * Classe utilitária para tratar paginação de dados em requisições e respostas.
 *
 * @package App\Utils
 */
class Paginador
{
    /**
     * Extrai e normaliza os parâmetros de paginação da requisição.
     *
     * @param array $params Parâmetros recebidos (ex: via query string).
     * @return array Retorna array com 'page', 'itemsPerPage', 'offset' e quaisquer filtros adicionais.
     */
    public static function extrairParametros(array $params): array
    {
        $page = isset($params['page']) && $params['page'] > 0 ? (int)$params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int)$params['itemsPerPage'] : 10;

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

    /**
     * Formata os dados paginados para resposta padrão de API.
     *
     * @param array $dados Lista de dados retornados.
     * @param int $total Quantidade total de registros.
     * @return array Array com chaves 'data' e 'total'.
     */
    public static function formatarResultado(array $dados, int $total): array
    {
        return [
            'data' => $dados,
            'total' => $total,
        ];
    }
}
