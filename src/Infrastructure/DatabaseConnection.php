<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Config\AppConfig;
use PDO;
use PDOException;
use RuntimeException;

final class DatabaseConnection
{
    public static function fromConfig(AppConfig $config): PDO
    {
        if (!$config->hasDatabaseConfig()) {
            throw new RuntimeException('Database configuration is incomplete.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config->dbHost,
            $config->dbPort,
            $config->dbName,
        );

        try {
            return new PDO($dsn, $config->dbUser, $config->dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed.', 0, $e);
        }
    }
}
