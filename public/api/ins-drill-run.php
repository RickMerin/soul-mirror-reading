<?php
declare(strict_types=1);
// TEMPORARY (2026-07-09): token-gated wrapper to run the CLI cancel-INS fire drill once. REMOVE after.
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');
$expected = 'c98ed674723b470bae495220e6f116ccd02d591624fc1f3d';
$given = $_GET['token'] ?? '';
if (!is_string($given) || !hash_equals($expected, $given)) { http_response_code(404); echo 'Not found.'; exit; }
$receipt = preg_replace('/[^A-Z0-9-]/', '', strtoupper((string)($_GET['receipt'] ?? '')));
$sku = preg_replace('/[^a-z0-9-]/', '', strtolower((string)($_GET['sku'] ?? '')));
if ($receipt === '' || $sku === '') { http_response_code(400); echo 'receipt and sku required'; exit; }
$root = dirname(__DIR__, 2);
$cmd = 'php ' . escapeshellarg($root . '/scripts/drill-cancel-ins.php') . ' ' . escapeshellarg($receipt) . ' ' . escapeshellarg($sku) . ' 2>&1';
$out = shell_exec($cmd);
echo $out === null ? 'shell_exec unavailable' : $out;
