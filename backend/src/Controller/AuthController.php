<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Usuario\Service;
use App\Utils\Response;
use App\Core\TokenManager;

/**
 * Controller responsável pela autenticação dos usuários.
 */
class AuthController extends BaseController
{
    /**
     * @param Service $service Serviço de autenticação de usuários.
     */
    public function __construct(
        private readonly Service $service
    ) {}

    /**
     * Realiza o login do usuário, gerando um token de autenticação.
     *
     * @param array $dados Dados contendo e-mail e senha.
     * @return void
     */
    public function login(array $dados): void
    {
        try {
            $usuario = $this->service->autenticar($dados['email'], $dados['senha']);

            $token = TokenManager::gerar([
                'id'    => $usuario->getId(),
                'email' => $usuario->getEmail(),
                'papel' => $usuario->getPapel()
            ]);

            Response::json([
                'token'   => $token,
                'usuario' => [
                    'id'    => $usuario->getId(),
                    'nome'  => $usuario->getNome(),
                    'email' => $usuario->getEmail(),
                    'papel' => $usuario->getPapel()
                ]
            ], HttpStatus::OK);
        } catch (\Exception $e) {
            Response::json([
                'error' => $e->getMessage()
            ], HttpStatus::UNAUTHORIZED);
        }
    }

    /**
     * Retorna os dados do usuário autenticado.
     *
     * @return void
     */
    public function me(): void
    {
        $usuario = $this->usuarioAutenticado();
        Response::json([
            'id'    => $usuario->getId(),
            'nome'  => $usuario->getNome(),
            'email' => $usuario->getEmail(),
            'papel' => $usuario->getPapel()
        ]);
    }
}
