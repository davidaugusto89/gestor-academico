<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Core\HttpStatus;
use App\Domain\Usuario\Service;
use App\Utils\Response;
use App\Core\TokenManager;

class AuthController extends BaseController
{
    public function __construct(
        private readonly Service $service
    ) {}

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
