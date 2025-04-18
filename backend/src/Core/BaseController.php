<?php

namespace App\Core;

use App\Core\Exceptions\UnauthorizedException;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\Repository;

/**
 * Classe base para todos os controllers.
 * Fornece métodos utilitários para resposta JSON e autenticação.
 */
class BaseController
{
    private ?Entity $usuario = null;

    private TokenManager $tokenManager;
    private Repository $usuarioRepository;

    /**
     * @param TokenManager|null $tokenManager
     * @param Repository|null $usuarioRepository
     */
    public function __construct(?TokenManager $tokenManager = null, ?Repository $usuarioRepository = null)
    {
        $this->tokenManager = $tokenManager ?? new TokenManager();
        $this->usuarioRepository = $usuarioRepository ?? new \App\Domain\Usuario\RepositoryImpl(Database::connect());
    }

    /**
     * Retorna uma resposta JSON.
     *
     * @param array $data
     * @param int $status
     * @return void
     */
    protected function json(array $data, int $status = HttpStatus::OK): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Retorna uma resposta de sucesso.
     *
     * @param string $mensagem
     * @return void
     */
    protected function success(string $mensagem = 'Operação realizada com sucesso'): void
    {
        $this->json(['message' => $mensagem]);
    }

    /**
     * Retorna uma resposta de erro.
     *
     * @param string $mensagem
     * @param int $status
     * @return void
     */
    protected function error(string $mensagem, int $status = HttpStatus::BAD_REQUEST): void
    {
        $this->json(['error' => $mensagem], $status);
    }

    /**
     * Retorna o usuário autenticado com base no token do cabeçalho Authorization.
     *
     * @throws UnauthorizedException
     * @return Entity
     */
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
        $dados = $this->tokenManager->validar($token);

        if (!$dados || !isset($dados['id'])) {
            throw new UnauthorizedException('Token inválido.');
        }

        $usuario = $this->usuarioRepository->buscarPorId($dados['id']);

        if (!$usuario) {
            throw new UnauthorizedException('Usuário não encontrado.');
        }

        return $this->usuario = $usuario;
    }
}
