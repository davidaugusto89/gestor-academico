<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Core\Exceptions\DatabaseConnectionException;

/**
 * Classe responsável por fornecer uma conexão PDO com o banco de dados.
 */
class Database
{
    /**
     * Cria e retorna uma conexão PDO com base nas variáveis de ambiente.
     *
     * @throws DatabaseConnectionException Se não for possível estabelecer a conexão.
     *
     * @return PDO Conexão ativa com o banco de dados.
     */
    public static function connect(): PDO
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $dbname = $_ENV['DB_NAME'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $pass = $_ENV['DB_PASS'] ?? '';

        try {
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new DatabaseConnectionException('Erro ao conectar ao banco de dados.', HttpStatus::INTERNAL_SERVER_ERROR, $e);
        }
    }
}
