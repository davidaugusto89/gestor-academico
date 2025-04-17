<?php

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function requestJson(string $method, string $uri, array $payload = []): array
    {
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        file_put_contents('php://input', json_encode($payload));

        ob_start();
        include __DIR__ . '/../public/index.php';
        $output = ob_get_clean();

        return json_decode($output, true);
    }
}
