<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Usuario\Service;
use App\Domain\Usuario\DTO;
use App\Utils\Paginador;
use App\Utils\Response;

/**
 * Controller responsável pelo gerenciamento de usuários.
 */
class UsuarioController extends BaseController
{
    /**
     * Serviço responsável pela lógica de negócios relacionada a usuários.
     *
     * @var Service
     */
    private readonly Service $service;

    /**
     * Construtor do controller de usuários.
     *
     * @param Service $service Instância do serviço de usuários.
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Cria um novo usuário com os dados fornecidos.
     *
     * @param array $dados Dados do usuário a serem criados.
     * @return void
     */
    public function criar(array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->criar($dto);
        Response::json(['mensagem' => 'Usuário cadastrado com sucesso.'], HttpStatus::CREATED);
    }

    /**
     * Lista usuários com suporte à paginação e ordenação.
     *
     * @param array $params Parâmetros de filtro e paginação.
     * @return void
     */
    public function listar(array $params): void
    {
        $paramsFilter = Paginador::extrairParametros($params);
        $retorno = $this->service->listarTodos($paramsFilter, 'nome:asc');

        $resultado = Paginador::formatarResultado($retorno['data'], $retorno['total']);
        Response::json($resultado, HttpStatus::OK);
    }

    /**
     * Busca um usuário pelo ID.
     *
     * @param int $id Identificador do usuário.
     * @return void
     */
    public function buscar(int $id): void
    {
        $usuario = $this->service->buscarPorId($id);
        Response::json($usuario, HttpStatus::OK);
    }

    /**
     * Busca um usuário pelo e-mail.
     *
     * @param string $email E-mail do usuário.
     * @return void
     */
    public function buscarPorEmail(string $email): void
    {
        $usuario = $this->service->buscarPorEmail($email);
        Response::json($usuario, HttpStatus::OK);
    }

    /**
     * Atualiza um usuário com os dados fornecidos.
     *
     * @param int $id Identificador do usuário.
     * @param array $dados Dados atualizados do usuário.
     * @return void
     */
    public function atualizar(int $id, array $dados): void
    {
        $dto = DTO::fromArray($dados);
        $this->service->atualizar($id, $dto);
        Response::json(['mensagem' => 'Usuário atualizado com sucesso.'], HttpStatus::OK);
    }

    /**
     * Remove um usuário pelo ID.
     *
     * @param int $id Identificador do usuário.
     * @return void
     */
    public function remover(int $id): void
    {
        $this->service->remover($id);
        Response::json(['mensagem' => 'Usuário removido com sucesso.'], HttpStatus::OK);
    }
}
