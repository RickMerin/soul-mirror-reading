<?php
declare(strict_types=1);
// TEMPORARY diagnostic endpoint (2026-07-08): token-gated tail of the INS audit log.
// Contains no emails or secrets by logger design. REMOVE after INS cancel diagnosis.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$expected = '08484679a4208583b86ab7f65d682ee3bf5e0d79c824a91d';
$given = $_GET['token'] ?? '';
if (!is_string($given) || !hash_equals($expected, $given)) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found.']);
    exit;
}
$path = dirname(__DIR__, 2) . '/storage/logs/clickbank-ins.log';
if (!is_file($path)) {
    echo json_encode(['exists' => false, 'path_checked' => 'storage/logs/clickbank-ins.log']);
    exit;
}
$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
$tail = array_slice($lines, -120);
echo json_encode([
    'exists' => true,
    'size_bytes' => filesize($path),
    'total_lines' => count($lines),
    'tail' => $tail,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
