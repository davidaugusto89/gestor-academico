<?php

namespace Tests\Fake;

use App\Core\BaseRepository;
use App\Core\QueryBuilderHelper;

class FakeRepository extends BaseRepository
{
    protected string $tabela = 'itens';

    protected function mapearParaEntidade(array $dados): object
    {
        return (object) [
            'id' => $dados['id'],
            'nome' => $dados['nome'],
        ];
    }
}
