<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;

final class MemberUrlBuilder
{
    public static function memberPath(string $scriptName): string
    {
        $basePath = self::requestBasePath($scriptName);
        return ($basePath === '' ? '' : $basePath) . '/member';
    }

    public static function loginPath(string $scriptName = ''): string
    {
        return self::memberPath(self::serverScriptName($scriptName)) . '/login.php';
    }

    public static function indexPath(string $scriptName = ''): string
    {
        return self::memberPath(self::serverScriptName($scriptName)) . '/index.php';
    }

    public static function logoutPath(string $scriptName = ''): string
    {
        return self::memberPath(self::serverScriptName($scriptName)) . '/logout.php';
    }

    /**
     * @param array<string, scalar|null> $query
     */
    public static function absoluteMemberUrl(AppConfig $config, string $script, array $query = []): ?string
    {
        $baseUrl = rtrim($config->appBaseUrl, '/');
        if ($baseUrl === '') {
            $origin = self::requestOrigin();
            if ($origin === null) {
                return null;
            }
            $basePath = self::requestBasePath(self::serverScriptName(''));
            $baseUrl = rtrim($origin . $basePath, '/');
        }

        $path = $baseUrl . '/member/' . ltrim($script, '/');
        if ($query === []) {
            return $path;
        }

        return $path . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    private static function requestBasePath(string $scriptName): string
    {
        $normalized = str_replace('\\', '/', $scriptName);
        $normalized = preg_replace('#/+#', '/', $normalized) ?? $normalized;

        if ($normalized === '' || $normalized[0] !== '/') {
            $normalized = '/' . ltrim($normalized, '/');
        }

        $directory = rtrim(str_replace('\\', '/', dirname($normalized)), '/');
        if ($directory === '' || $directory === '.') {
            return '';
        }

        foreach (['/public/member', '/member', '/public'] as $suffix) {
            if ($directory === $suffix) {
                return '';
            }
            if (str_ends_with($directory, $suffix)) {
                $base = substr($directory, 0, -strlen($suffix));
                return $base === '' ? '' : rtrim($base, '/');
            }
        }

        return $directory;
    }

    private static function serverScriptName(string $scriptName): string
    {
        if ($scriptName !== '') {
            return $scriptName;
        }

        $fromServer = $_SERVER['SCRIPT_NAME'] ?? '';
        return is_string($fromServer) ? $fromServer : '';
    }

    private static function requestOrigin(): ?string
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (!is_string($host) || $host === '') {
            return null;
        }

        $isLocalHost = str_starts_with($host, 'localhost') || str_starts_with($host, '127.0.0.1');
        $https = ($_SERVER['HTTPS'] ?? '') === 'on' || ($_SERVER['SERVER_PORT'] ?? '') === '443';
        $scheme = $https && !$isLocalHost ? 'https' : 'http';

        return $scheme . '://' . $host;
    }
}
