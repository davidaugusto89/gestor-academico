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
     * @param TokenManager $tokenManager Gerador de token de autenticação.
     * @param Response $response Resposta HTTP.
     */
    public function __construct(
        private readonly Service $service,
        private readonly TokenManager $tokenManager,
        private readonly Response $response
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

            $token = $this->tokenManager->gerar([
                'id'    => $usuario->getId(),
                'email' => $usuario->getEmail(),
                'papel' => $usuario->getPapel()
            ]);

            $this->response->json([
                'token'   => $token,
                'usuario' => [
                    'id'    => $usuario->getId(),
                    'nome'  => $usuario->getNome(),
                    'email' => $usuario->getEmail(),
                    'papel' => $usuario->getPapel()
                ]
            ], HttpStatus::OK);
        } catch (\Exception $e) {
            $this->response->json([
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
        $this->response->json([
            'id'    => $usuario->getId(),
            'nome'  => $usuario->getNome(),
            'email' => $usuario->getEmail(),
            'papel' => $usuario->getPapel()
        ]);
    }
}
