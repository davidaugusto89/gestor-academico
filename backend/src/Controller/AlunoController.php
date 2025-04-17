<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Aluno\Service;
use App\Domain\Aluno\DTO;
use App\Utils\Response;

class AlunoController extends BaseController
{
    public function __construct(
        private readonly Service $service
    ) {}

    public function criar(array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->criar($dto);

        Response::json(['mensagem' => 'Aluno cadastrado com sucesso.'], HttpStatus::CREATED);
    }

    public function listar(): void
    {
        $alunos = $this->service->listarTodos();
        Response::json($alunos, HttpStatus::OK);
    }

    public function buscar(int $id): void
    {
        $aluno = $this->service->buscarPorId($id);
        Response::json($aluno, HttpStatus::OK);
    }

    public function buscarPorNome(string $nome): void
    {
        $alunos = $this->service->buscarPorNome($nome);
        Response::json($alunos, HttpStatus::OK);
    }

    public function atualizar(int $id, array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->atualizar($id, $dto);

        Response::json(['mensagem' => 'Aluno atualizado com sucesso.'], HttpStatus::OK);
    }

    public function remover(int $id): void
    {
        $this->service->remover($id);
        Response::json(['mensagem' => 'Aluno removido com sucesso.'], HttpStatus::OK);
    }
}
