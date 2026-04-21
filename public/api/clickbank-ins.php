<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Method not allowed.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Placeholder endpoint for future ClickBank INS integration.
// Next iteration: validate INS signature, normalize purchase payload, and write into `purchases`.
http_response_code(501);
echo json_encode([
    'error' => 'ClickBank INS handler is not implemented yet.',
], JSON_UNESCAPED_UNICODE);
