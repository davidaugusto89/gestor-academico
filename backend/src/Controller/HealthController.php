<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Utils\Response;

/**
 * Controller responsável por verificar a saúde da aplicação.
 */
class HealthController extends BaseController
{
    /**
     * Retorna um status indicando que a API está online.
     *
     * @return void
     */
    public function health(): void
    {
        Response::json([
            'status' => 'It’s a trap! Just kidding, API is online.',
            'force' => 'strong',
            'timestamp' => time()
        ]);
    }
}
