<?php
 
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

if (is_readable($projectRoot . '/.env')) {
    Dotenv\Dotenv::createImmutable($projectRoot)->safeLoad();
}

$secretKey = $_ENV['CLICKBANK_SECRET_KEY'] ?? getenv('CLICKBANK_SECRET_KEY') ?: '';
if ($secretKey === '') {
    http_response_code(500);
    error_log('clickbank.php missing CLICKBANK_SECRET_KEY');
    exit;
}
 
// get JSON from raw body...
$message = json_decode(file_get_contents('php://input'));
 
// Pull out the encrypted notification and the initialization vector for
// AES/CBC/PKCS5Padding decryption
$encrypted = $message->{'notification'};
$iv = $message->{'iv'};
error_log("IV: $iv");
 
// decrypt the body...
$decrypted = trim(
 openssl_decrypt(base64_decode($encrypted),
 'AES-256-CBC',
 substr(sha1($secretKey), 0, 32),
 OPENSSL_RAW_DATA,
 base64_decode($iv)), "\0..\32");
  
error_log("Decrypted: $decrypted");
 
////UTF8 Encoding, remove escape back slashes, and convert the decrypted string to a JSON object...
$sanitizedData = utf8_encode(stripslashes($decrypted));
$order = json_decode($decrypted);
 
// Ready to rock and roll - If the decoding of the JSON string wasn't
// successful, then you can assume the notification wasn't encrypted
// with your secret key.
 
?>