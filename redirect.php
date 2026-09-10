<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

$code = $_GET['code'] ?? '';

if (!preg_match('/^[A-Za-z0-9_-]{3,32}$/', $code)) {
    http_response_code(404);
    exit('Short URL not found.');
}

$stmt = $pdo->prepare(
    'SELECT id, original_url, clicks, expires_at, is_active
     FROM urls
     WHERE short_code = ?
     LIMIT 1'
);

$stmt->execute([$code]);

$url = $stmt->fetch();

if (!$url) {
    http_response_code(404);
    exit('Short URL not found.');
}

if (!$url['is_active']) {
    http_response_code(410);
    exit('This short URL has been disabled.');
}

if (
    $url['expires_at'] !== null &&
    strtotime($url['expires_at']) <= time()
) {
    http_response_code(410);
    exit('This short URL has expired.');
}

$stmt = $pdo->prepare(
    'UPDATE urls
     SET clicks = clicks + 1
     WHERE id = ?'
);

$stmt->execute([$url['id']]);

header(
    'Location: ' . $url['original_url'],
    true,
    302
);

exit;