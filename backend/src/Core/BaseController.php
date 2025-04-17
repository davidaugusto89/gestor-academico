<?php

namespace App\Core;

use App\Core\Exceptions\UnauthorizedException;
use App\Core\TokenManager;
use App\Domain\Usuario\Repository;
use App\Domain\Usuario\Entity;

class BaseController
{
    private ?Entity $usuario = null;

    protected function json(array $data, int $status = HttpStatus::OK): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function success(string $mensagem = 'Operação realizada com sucesso'): void
    {
        $this->json(['message' => $mensagem]);
    }

    protected function error(string $mensagem, int $status = HttpStatus::BAD_REQUEST): void
    {
        $this->json(['error' => $mensagem], $status);
    }

    protected function usuarioAutenticado(): Entity
    {
        if ($this->usuario) {
            return $this->usuario;
        }

        $headers = getallheaders();
        $authorization = $headers['Authorization'] ?? '';

        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new UnauthorizedException('Token ausente ou inválido.');
        }

        $token = trim(str_replace('Bearer', '', $authorization));

        $dados = TokenManager::validar($token);
        if (!$dados || !isset($dados['id'])) {
            throw new UnauthorizedException('Token inválido.');
        }

        $repo = new \App\Domain\Usuario\RepositoryImpl(\App\Core\Database::connect());

        $usuario = $repo->buscarPorId($dados['id']);

        if (!$usuario) {
            throw new UnauthorizedException('Usuário não encontrado.');
        }

        return $this->usuario = $usuario;
    }
}
