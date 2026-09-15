<?php
declare(strict_types=1);
const APP_NAME='INFOROVA';
const APP_TZ='Asia/Dhaka';
const APP_SECRET='';
function app_secret(): string { $v=getenv('ED_SHARED_SECRET')?:APP_SECRET; if($v==='') $v='CHANGE_THIS_TO_A_LONG_RANDOM_SECRET'; return $v; }
date_default_timezone_set(APP_TZ);
function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

function app_url(): string {
    $env = getenv('APP_URL');
    if ($env) return rtrim($env, '/');
    $scheme = !empty($_SERVER['HTTP_X_FORWARDED_PROTO'])
        ? $_SERVER['HTTP_X_FORWARDED_PROTO']
        : ((($_SERVER['HTTPS'] ?? '') === 'on') ? 'https' : 'http');
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme.'://'.$host;
}
function base_url(string $path=''): string { return app_url().'/'.ltrim($path,'/'); }
function redirect_to(string $path): never { header('Location: '.base_url($path)); exit; }

set_exception_handler(function (Throwable $e) {
    http_response_code(500);
    if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><meta charset="utf-8"><body style="font-family:sans-serif;max-width:640px;margin:60px auto;padding:0 20px;color:#222;line-height:1.6">'
        .'<h2 style="color:#c00">Something went wrong</h2>'
        .'<p><strong>'.htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8').'</strong></p>'
        .'<p style="color:#777;font-size:13px">'.htmlspecialchars(get_class($e), ENT_QUOTES, 'UTF-8').' — '
        .htmlspecialchars(basename($e->getFile()), ENT_QUOTES, 'UTF-8').' line '.$e->getLine().'</p>'
        .'</body>';
});
