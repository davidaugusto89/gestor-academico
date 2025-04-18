<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!function_exists('getallheaders')) {
    function getallheaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $header = ucwords(str_replace('_', '-', strtolower(substr($name, 5))), '-');
                $headers[$header] = $value;
            }
        }
        return $headers;
    }
}