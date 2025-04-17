<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Turma\DTO;
use App\Domain\Turma\Service;
use App\Utils\Paginador;
use App\Utils\Response;

class TurmaController extends BaseController
{
    public function __construct(
        private readonly Service $service
    ) {}

    public function criar(array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->criar($dto);

        Response::json(['mensagem' => 'Turma cadastrada com sucesso.'], HttpStatus::CREATED);
    }

    public function listar(array $params): void
    {
        $paramsFilter = Paginador::extrairParametros($params);
        $retorno = $this->service->listarTodos($paramsFilter, 'nome:asc',);

        $resultado = Paginador::formatarResultado(
            $retorno['data'],
            $retorno['total']
        );

        Response::json($resultado, HttpStatus::OK);
    }

    public function buscar(int $id): void
    {
        $turma = $this->service->buscarPorId($id);
        Response::json($turma, HttpStatus::OK);
    }

    public function buscarPorNome(string $nome): void
    {
        $turmas = $this->service->buscarPorNome($nome);
        Response::json($turmas, HttpStatus::OK);
    }

    public function atualizar(int $id, array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->atualizar($id, $dto);

        Response::json(['mensagem' => 'Turma atualizada com sucesso.'], HttpStatus::OK);
    }

    public function remover(int $id): void
    {
        $this->service->remover($id);
        Response::json(['mensagem' => 'Turma removida com sucesso.'], HttpStatus::OK);
    }
}
