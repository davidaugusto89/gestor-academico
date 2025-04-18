<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Matricula\DTO;
use App\Domain\Matricula\Service;
use App\Utils\Paginador;
use App\Utils\Response;

/**
 * Controller responsável por gerenciar as matrículas de alunos em turmas.
 */
class MatriculaController extends BaseController
{
    /**
     * @param Service $service Serviço de regras de negócio para matrícula.
     */
    public function __construct(
        private readonly Service $service
    ) {}

    /**
     * Realiza a matrícula de um aluno em uma turma.
     *
     * @param array $dados Dados da matrícula no formato esperado pelo DTO.
     * @return void
     */
    public function matricular(array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->matricular($dto);

        Response::json(['mensagem' => 'Matrícula realizada com sucesso.'], HttpStatus::CREATED);
    }

    /**
     * Lista todas as matrículas com paginação e ordenação.
     *
     * @param array $params Parâmetros de filtro e paginação.
     * @return void
     */
    public function listar(array $params): void
    {
        $paramsFilter = Paginador::extrairParametros($params);
        $retorno = $this->service->listarTodos($paramsFilter, 'data_matricula:desc');

        $resultado = Paginador::formatarResultado(
            $retorno['data'],
            $retorno['total']
        );

        Response::json($resultado, HttpStatus::OK);
    }

    /**
     * Lista todos os alunos matriculados em uma turma específica.
     *
     * @param int $turmaId ID da turma.
     * @return void
     */
    public function listarPorTurma(int $turmaId): void
    {
        $alunos = $this->service->listarPorTurma($turmaId);
        Response::json($alunos, HttpStatus::OK);
    }

    /**
     * Remove uma matrícula com base no aluno e turma informados.
     *
     * @param array $dados Dados contendo aluno_id e turma_id.
     * @return void
     */
    public function remover(array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->remover($dto);

        Response::json(['mensagem' => 'Matrícula removida com sucesso.'], HttpStatus::OK);
    }
}
