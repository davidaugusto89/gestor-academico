<?php

namespace App\Core;

use PDO;

class QueryBuilderHelper
{
    /**
     * Constrói e executa uma query com filtros, paginação e ordenação.
     *
     * @param PDO $pdo Conexão PDO ativa
     * @param string $sqlBase Query base SEM WHERE, ORDER BY ou LIMIT
     * @param array $params Parâmetros da requisição (filtros, paginação)
     * @param array $camposPermitidos Campos permitidos para filtro
     * @param string $ordem Ex: "nome:asc"
     * @param string $alias Alias da tabela principal
     * @param callable|null $map Função de mapeamento dos dados (opcional)
     * @param string|null $sqlCount SQL de contagem personalizado (opcional)
     * @param string|null $groupBy Campo(s) para GROUP BY (opcional)
     *
     * @return array ['data' => [], 'total' => 0]
     */
    public static function paginarResultado(
        PDO $pdo,
        string $sqlBase,
        array $params,
        array $camposPermitidos,
        string $ordem = 'id:asc',
        string $alias = 't',
        ?callable $map = null,
        ?string $sqlCount = null,
        ?string $groupBy = null
    ): array {
        list($colunaOrdenacao, $direcao) = explode(':', $ordem);
        $direcao = strtolower($direcao) === 'desc' ? 'DESC' : 'ASC';

        $page = isset($params['page']) ? (int) $params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int) $params['itemsPerPage'] : 10;
        $offset = ($page - 1) * $itemsPerPage;

        $where = '';
        $binds = [];

        foreach ($params as $key => $value) {
            if (in_array($key, $camposPermitidos) && $value !== null) {
                $param = ":{$key}";
                $binds[$param] = $key === 'nome' ? "%{$value}%" : $value;
                $where .= $key === 'nome'
                    ? " AND {$alias}.{$key} LIKE {$param}"
                    : " AND {$alias}.{$key} = {$param}";
            }
        }

        $whereClause = $where ? ' WHERE ' . substr($where, 5) : '';
        $groupClause = $groupBy ? " GROUP BY {$groupBy}" : '';

        $sql = "$sqlBase {$whereClause} {$groupClause} ORDER BY {$alias}.{$colunaOrdenacao} {$direcao} LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        foreach ($binds as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($map) {
            $data = array_map($map, $data);
        }

        // Contagem total
        if ($sqlCount) {
            $stmtCount = $pdo->prepare($sqlCount . ' ' . $whereClause);
        } else {
            $stmtCount = $pdo->prepare("SELECT COUNT(*) as total FROM ({$sqlBase} {$whereClause}) as sub");
        }

        foreach ($binds as $key => $value) {
            $stmtCount->bindValue($key, $value);
        }
        $stmtCount->execute();
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'data' => $data,
            'total' => (int) $total,
        ];
    }
}
