<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../config/app.php';

$token = $_GET['token'] ?? '';

$valid = false;
if ($token !== '') {
    $raw = base64_decode(strtr($token, '-_', '+/') ?: '', true);
    if ($raw !== false && str_contains($raw, '.')) {
        [$payload, $sig] = explode('.', $raw, 2);
        $data = json_decode($payload, true);
        $valid = is_array($data)
            && ($data['exp'] ?? 0) >= time()
            && hash_equals(hash_hmac('sha256', $payload, app_secret()), $sig);
    }
}

if (!$valid) {
    redirect_to('index.php');
}

// Final destination = the "BDXLink verified return URL" set in Admin -> Settings
$destination = setting('bdxlink_return_url', '');

if ($destination === '') {
    redirect_to('index.php');
}

header('Location: ' . $destination . '?token=' . rawurlencode($token));
exit;
