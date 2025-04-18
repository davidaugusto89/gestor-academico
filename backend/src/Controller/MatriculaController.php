<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Matricula\DTO;
use App\Domain\Matricula\Service;
use App\Utils\Response;

class MatriculaController extends BaseController
{
    public function __construct(
        private readonly Service $service
    ) {}

    public function matricular(array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->matricular($dto);

        Response::json(['mensagem' => 'Matrícula realizada com sucesso.'], HttpStatus::CREATED);
    }

    public function listarPorTurma(int $turmaId): void
    {
        $alunos = $this->service->listarPorTurma($turmaId);
        Response::json($alunos, HttpStatus::OK);
    }

    public function remover(array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->remover($dto);

        Response::json(['mensagem' => 'Matrícula removida com sucesso.'], HttpStatus::OK);
    }
}
