#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);
$pdo = DatabaseConnection::fromConfig($config);
$migrationsDir = $projectRoot . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';

if (!is_dir($migrationsDir)) {
    throw new RuntimeException('Migrations directory not found: ' . $migrationsDir);
}

createSchemaMigrationsTable($pdo);

$migrationFiles = getMigrationFiles($migrationsDir);
$appliedMigrations = getAppliedMigrations($pdo);
$pendingMigrations = array_values(array_diff($migrationFiles, $appliedMigrations));

if ($pendingMigrations === []) {
    echo 'No pending migrations.' . PHP_EOL;
    exit(0);
}

echo 'Applying ' . count($pendingMigrations) . ' migration(s)...' . PHP_EOL;

foreach ($pendingMigrations as $migrationFile) {
    $filePath = $migrationsDir . DIRECTORY_SEPARATOR . $migrationFile;
    $sql = file_get_contents($filePath);

    if ($sql === false) {
        throw new RuntimeException('Unable to read migration file: ' . $migrationFile);
    }

    echo '-> ' . $migrationFile . PHP_EOL;

    try {
        // MySQL auto-commits DDL, so wrapping CREATE/ALTER statements in a
        // transaction can cause "There is no active transaction" on commit.
        $pdo->exec($sql);

        $stmt = $pdo->prepare(
            'INSERT INTO schema_migrations (migration_file, applied_at) VALUES (:migration_file, NOW())'
        );
        $stmt->execute(['migration_file' => $migrationFile]);
    } catch (\Throwable $e) {
        throw new RuntimeException('Migration failed for ' . $migrationFile . '.', 0, $e);
    }
}

echo 'Migrations completed successfully.' . PHP_EOL;

/**
 * @return list<string>
 */
function getMigrationFiles(string $migrationsDir): array
{
    $entries = scandir($migrationsDir);
    if ($entries === false) {
        throw new RuntimeException('Unable to read migrations directory.');
    }

    $files = array_values(array_filter(
        $entries,
        static fn (string $entry): bool => str_ends_with($entry, '.sql')
    ));

    sort($files, SORT_NATURAL);

    return $files;
}

function createSchemaMigrationsTable(PDO $pdo): void
{
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS schema_migrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration_file VARCHAR(255) NOT NULL,
    applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_schema_migrations_file (migration_file)
)
SQL;

    $pdo->exec($sql);
}

/**
 * @return list<string>
 */
function getAppliedMigrations(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT migration_file FROM schema_migrations ORDER BY migration_file ASC');
    if ($stmt === false) {
        throw new RuntimeException('Unable to fetch applied migrations.');
    }

    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return array_values(array_filter(
        $rows === false ? [] : $rows,
        static fn (mixed $value): bool => is_string($value)
    ));
}
