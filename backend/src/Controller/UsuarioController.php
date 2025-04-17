<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Usuario\Service;
use App\Domain\Usuario\DTO;
use App\Utils\Response;

class UsuarioController extends BaseController
{
    public function __construct(
        private readonly Service $service
    ) {}

    public function criar(array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->criar($dto);

        Response::json(['mensagem' => 'Usuário cadastrado com sucesso.'], HttpStatus::CREATED);
    }

    public function listar(): void
    {
        $usuarios = $this->service->listarTodos();
        Response::json($usuarios, HttpStatus::OK);
    }

    public function buscar(int $id): void
    {
        $usuario = $this->service->buscarPorId($id);
        Response::json($usuario, HttpStatus::OK);
    }

    public function buscarPorEmail(string $email): void
    {
        $usuario = $this->service->buscarPorEmail($email);
        Response::json($usuario, HttpStatus::OK);
    }

    public function atualizar(int $id, array $dados): void
    {
        $dto = DTO::fromArray($dados);

        $this->service->atualizar($id, $dto);

        Response::json(['mensagem' => 'Usuário atualizado com sucesso.'], HttpStatus::OK);
    }

    public function remover(int $id): void
    {
        $this->service->remover($id);
        Response::json(['mensagem' => 'Usuário removido com sucesso.'], HttpStatus::OK);
    }
}
