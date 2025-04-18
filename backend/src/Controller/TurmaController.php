<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Turma\DTO;
use App\Domain\Turma\Service;
use App\Utils\Paginador;
use App\Utils\Response;

/**
 * Controller responsável pelo gerenciamento de turmas.
 */
class TurmaController extends BaseController
{
    /**
     * @param Service $service Serviço responsável pela lógica de turmas.
     */
    public function __construct(
        private readonly Service $service
    ) {}

    /**
     * Cadastra uma nova turma.
     *
     * @param array $dados Dados da turma.
     * @return void
     */
    public function criar(array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->criar($dto);

        Response::json(['mensagem' => 'Turma cadastrada com sucesso.'], HttpStatus::CREATED);
    }

    /**
     * Lista todas as turmas com suporte à paginação.
     *
     * @param array $params Parâmetros de filtro e paginação.
     * @return void
     */
    public function listar(array $params): void
    {
        $paramsFilter = Paginador::extrairParametros($params);
        $retorno = $this->service->listarTodos($paramsFilter, 'nome:asc');

        $resultado = Paginador::formatarResultado(
            $retorno['data'],
            $retorno['total']
        );

        Response::json($resultado, HttpStatus::OK);
    }

    /**
     * Busca uma turma pelo ID.
     *
     * @param int $id ID da turma.
     * @return void
     */
    public function buscar(int $id): void
    {
        $turma = $this->service->buscarPorId($id);
        Response::json($turma, HttpStatus::OK);
    }

    /**
     * Busca turmas pelo nome.
     *
     * @param string $nome Nome da turma.
     * @return void
     */
    public function buscarPorNome(string $nome): void
    {
        $turmas = $this->service->buscarPorNome($nome);
        Response::json($turmas, HttpStatus::OK);
    }

    /**
     * Atualiza os dados de uma turma existente.
     *
     * @param int $id ID da turma.
     * @param array $dados Novos dados da turma.
     * @return void
     */
    public function atualizar(int $id, array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->atualizar($id, $dto);

        Response::json(['mensagem' => 'Turma atualizada com sucesso.'], HttpStatus::OK);
    }

    /**
     * Remove uma turma.
     *
     * @param int $id ID da turma a ser removida.
     * @return void
     */
    public function remover(int $id): void
    {
        $this->service->remover($id);
        Response::json(['mensagem' => 'Turma removida com sucesso.'], HttpStatus::OK);
    }
}
