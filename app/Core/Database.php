<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {

            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['user'],
                    $config['pass'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        // ESTA LINHA ABAIXO resolve o fuso horário direto na conexão MySQL
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-03:00'"
                    ]
                );
            } catch (PDOException $e) {
                die("Erro ao conectar no banco: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
