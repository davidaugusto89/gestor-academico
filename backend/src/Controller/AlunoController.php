<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Aluno\Service;
use App\Domain\Aluno\DTO;
use App\Utils\Paginador;
use App\Utils\Response;

/**
 * Controller responsável por lidar com as requisições relacionadas aos alunos.
 */
class AlunoController extends BaseController
{
    /**
     * @param Service $service Serviço de regras de negócio para alunos.
     */
    public function __construct(
        private readonly Service $service
    ) {}

    /**
     * Cadastra um novo aluno.
     *
     * @param array $dados Dados do aluno enviados pela requisição.
     * @return void
     */
    public function criar(array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->criar($dto);

        Response::json(['mensagem' => 'Aluno cadastrado com sucesso.'], HttpStatus::CREATED);
    }

    /**
     * Lista os alunos com filtros e paginação.
     *
     * @param array $params Parâmetros da query string (página, filtros, etc).
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
     * Busca um aluno pelo ID.
     *
     * @param int $id ID do aluno.
     * @return void
     */
    public function buscar(int $id): void
    {
        $aluno = $this->service->buscarPorId($id);
        Response::json($aluno, HttpStatus::OK);
    }

    /**
     * Busca alunos pelo nome.
     *
     * @param array $dados Dados contendo o nome a ser buscado.
     * @return void
     */
    public function buscarPorNome(array $dados): void
    {
        $nome = $dados['nome'] ?? '';
        $alunos = $this->service->buscarPorNome($nome);
        Response::json($alunos, HttpStatus::OK);
    }

    /**
     * Atualiza os dados de um aluno.
     *
     * @param int $id ID do aluno a ser atualizado.
     * @param array $dados Novos dados para o aluno.
     * @return void
     */
    public function atualizar(int $id, array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->atualizar($id, $dto);

        Response::json(['mensagem' => 'Aluno atualizado com sucesso.'], HttpStatus::OK);
    }

    /**
     * Remove um aluno pelo ID.
     *
     * @param int $id ID do aluno a ser removido.
     * @return void
     */
    public function remover(int $id): void
    {
        $this->service->remover($id);
        Response::json(['mensagem' => 'Aluno removido com sucesso.'], HttpStatus::OK);
    }
}
