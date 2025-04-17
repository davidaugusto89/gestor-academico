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
        $dto = new DTO(
            $dados['aluno_id'],
            $dados['turma_id']
        );

        $this->service->matricular($dto);

        Response::json(['mensagem' => 'Matrícula realizada com sucesso.'], HttpStatus::CREATED);
    }

    public function listarPorTurma(int $turmaId): void
    {
        $alunos = $this->service->listarPorTurma($turmaId);
        Response::json($alunos, HttpStatus::OK);
    }

    public function remover(int $alunoId, int $turmaId): void
    {
        $this->service->remover($alunoId, $turmaId);
        Response::json(['mensagem' => 'Matrícula removida com sucesso.'], HttpStatus::OK);
    }
}
