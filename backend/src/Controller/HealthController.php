<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Utils\Response;

class HealthController extends BaseController
{
    public function health(): void
    {
        Response::json([
            'status' => 'It’s a trap! Just kidding, API is online.',
            'force' => 'strong',
            'timestamp' => time()
        ]);
    }
}
