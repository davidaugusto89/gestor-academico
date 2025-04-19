<?php

namespace App\Controller;

use App\Utils\Response;

/**
 * Controller responsável por verificar a saúde da aplicação.
 */
class HealthController
{
    /**
     * @param Response $response Serviço responsável pelo envio de respostas JSON.
     */
    public function __construct(
        private readonly Response $response
    ) {}

    /**
     * Retorna um status indicando que a API está online.
     *
     * @return void
     */
    public function health(): void
    {
        $this->response->json([
            'status' => 'It’s a trap! Just kidding, API is online.',
            'force' => 'strong',
            'timestamp' => time()
        ]);
    }
}
