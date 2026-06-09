<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;

final class MemberUrlBuilder
{
    public static function memberPath(string $scriptName): string
    {
        $base = self::siteBasePath($scriptName);
        return ($base === '' ? '' : $base) . '/member';
    }

    public static function loginPath(string $scriptName = ''): string
    {
        $base = self::siteBasePath(self::serverScriptName($scriptName));
        return ($base === '' ? '' : $base) . '/login.php';
    }

    public static function indexPath(string $scriptName = ''): string
    {
        return self::memberPath(self::serverScriptName($scriptName)) . '/index.php';
    }

    /**
     * Member index path for redirects when {@see AppConfig::appBaseUrl} is the canonical public URL.
     * Avoids inferring the site prefix from {@see $_SERVER} (WAMP/Windows can leak a drive path into URL paths).
     */
    public static function memberIndexPathFromAppBaseUrl(string $appBaseUrl): string
    {
        $base = rtrim($appBaseUrl, '/');
        if ($base === '') {
            return self::indexPath();
        }

        $parts = parse_url($base);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            return self::indexPath();
        }

        if (!in_array($parts['scheme'], ['http', 'https'], true)) {
            return self::indexPath();
        }

        $path = rtrim($parts['path'] ?? '', '/');
        $path = self::stripWampFileUrlPathPrefix($path);
        $path = rtrim($path, '/');
        if ($path !== '' && self::looksLikeFilesystemPath(self::normalizeUrlLikeScriptPath($path))) {
            return self::indexPath();
        }

        return ($path === '' ? '' : $path) . '/member/index.php';
    }

    public static function logoutPath(string $scriptName = ''): string
    {
        $base = self::siteBasePath(self::serverScriptName($scriptName));
        return ($base === '' ? '' : $base) . '/logout.php';
    }

    public static function apiPath(string $scriptName, string $requestScriptName = ''): string
    {
        $base = self::siteBasePath(self::serverScriptName($requestScriptName));
        return ($base === '' ? '' : $base) . '/api/' . ltrim($scriptName, '/');
    }

    /**
     * Site URL path prefix (no trailing slash), e.g. '' at domain root or '/soul-mirror-reading'.
     */
    private static function siteBasePath(string $scriptName): string
    {
        $fromEnv = self::appBaseUrlPathPrefix();
        if ($fromEnv !== null) {
            return $fromEnv === '' ? '' : $fromEnv;
        }

        $basePath = self::sanitizeUrlBasePath(self::requestBasePath($scriptName));
        return $basePath === '' ? '' : $basePath;
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
            $basePath = self::sanitizeUrlBasePath(self::requestBasePath(self::serverScriptName('')));
            $baseUrl = rtrim($origin . $basePath, '/');
        }

        $path = $baseUrl . '/member/' . ltrim($script, '/');
        if ($query === []) {
            return $path;
        }

        return $path . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * Path prefix from APP_BASE_URL (e.g. /soul-mirror-reading) when set and valid.
     * Null means "not configured — infer from SCRIPT_NAME".
     */
    private static function appBaseUrlPathPrefix(): ?string
    {
        $raw = $_ENV['APP_BASE_URL'] ?? getenv('APP_BASE_URL');
        if (!is_string($raw)) {
            return null;
        }
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }

        $parts = parse_url($raw);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            return null;
        }

        $path = rtrim($parts['path'] ?? '', '/');
        $path = self::stripWampFileUrlPathPrefix($path);
        $path = rtrim($path, '/');
        if ($path !== '' && self::looksLikeFilesystemPath(self::normalizeUrlLikeScriptPath($path))) {
            return null;
        }

        // Host-only URL (e.g. http://localhost or https://example.com) — do not treat as "/member at web root";
        // infer the site prefix from the request (WAMP subdirectory installs).
        if ($path === '') {
            return null;
        }

        return $path;
    }

    private static function requestBasePath(string $scriptName): string
    {
        foreach (self::scriptPathCandidates($scriptName) as $normalized) {
            if ($normalized === '') {
                continue;
            }
            $dejunked = self::normalizeUrlLikeScriptPath(self::stripWampFileUrlPathPrefix($normalized));
            if ($dejunked === '' || self::looksLikeFilesystemPath($dejunked)) {
                continue;
            }
            $stripped = self::stripPublicAndMemberFromDirectory($dejunked);
            if ($stripped !== null) {
                return self::sanitizeUrlBasePath($stripped);
            }
        }

        $fromUri = self::basePathFromRequestUri();
        if ($fromUri !== null) {
            return self::sanitizeUrlBasePath($fromUri);
        }

        return '';
    }

    /**
     * @return list<string>
     */
    private static function scriptPathCandidates(string $scriptName): array
    {
        $out = [];
        foreach ([$scriptName, $_SERVER['PHP_SELF'] ?? ''] as $raw) {
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            $normalized = self::normalizeUrlLikeScriptPath($raw);
            if ($normalized !== '') {
                $out[] = $normalized;
            }
        }

        return array_values(array_unique($out));
    }

    private static function normalizeUrlLikeScriptPath(string $scriptName): string
    {
        $normalized = str_replace('\\', '/', $scriptName);
        $normalized = preg_replace('#/+#', '/', $normalized) ?? $normalized;

        if ($normalized === '') {
            return '';
        }

        if ($normalized[0] !== '/') {
            $normalized = '/' . ltrim($normalized, '/');
        }

        return $normalized;
    }

    /**
     * WAMP/PHP on Windows can yield URL paths with an embedded drive path after the host, e.g.
     * /C:/wamp64/www/soul-mirror-reading/... – strip the Windows web-root prefix to a real site path.
     */
    private static function stripWampFileUrlPathPrefix(string $path): string
    {
        if ($path === '' || $path[0] !== '/') {
            $path = $path === '' ? '' : '/' . ltrim($path, '/');
        }
        if ($path === '') {
            return '';
        }

        // /C:/wamp.../www/<project>/...
        $one = preg_replace('#^/[A-Za-z]:(/[^/]+)*/(www|www64)/#', '/', $path) ?? $path;
        if ($one !== $path) {
            return preg_replace('#/+#', '/', $one) ?? $one;
        }

        // /C/wamp.../www/<project>/... (colon missing between drive letter and first segment in URL)
        $two = preg_replace('#^/[A-Za-z](/[^/]+)*/(www|www64)/#', '/', $path) ?? $path;
        if ($two !== $path) {
            return preg_replace('#/+#', '/', $two) ?? $two;
        }

        return $path;
    }

    /**
     * @return string Site path under the host, or '' if still not safe for a URL.
     */
    private static function sanitizeUrlBasePath(string $path): string
    {
        if ($path === '') {
            return '';
        }
        $stripped = self::stripWampFileUrlPathPrefix($path);
        $n = self::normalizeUrlLikeScriptPath($stripped);
        if (self::looksLikeFilesystemPath($n)) {
            return '';
        }

        return rtrim($n, '/');
    }

    /**
     * Windows/WAMP can expose a filesystem path in SCRIPT_NAME; never use that as a URL segment.
     */
    private static function looksLikeFilesystemPath(string $normalized): bool
    {
        if (str_contains($normalized, '\\')) {
            return true;
        }

        return (bool) preg_match('#(^|/)[A-Za-z]:/#', $normalized);
    }

    private static function stripPublicAndMemberFromDirectory(string $normalizedScriptPath): string
    {
        $directory = rtrim(str_replace('\\', '/', dirname($normalizedScriptPath)), '/');
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

    private static function basePathFromRequestUri(): ?string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (!is_string($uri) || $uri === '') {
            return null;
        }

        $path = parse_url($uri, PHP_URL_PATH);
        if (!is_string($path) || $path === '' || ($path[0] ?? '') !== '/') {
            return null;
        }

        $path = self::stripWampFileUrlPathPrefix($path);
        if ($path === '' || $path[0] !== '/') {
            return null;
        }

        $asFile = $path;
        $trimmed = rtrim(str_replace('\\', '/', $path), '/');
        if ($trimmed !== '' && !str_contains(basename($trimmed), '.')) {
            $asFile = $trimmed . '/index.php';
        }

        $normalized = self::normalizeUrlLikeScriptPath($asFile);
        if ($normalized === '' || self::looksLikeFilesystemPath($normalized)) {
            return null;
        }

        return self::stripPublicAndMemberFromDirectory($normalized);
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
