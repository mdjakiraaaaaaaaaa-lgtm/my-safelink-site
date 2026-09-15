<?php
declare(strict_types=1);
const APP_NAME='INFOROVA';
const APP_URL='https://inforova.com';
const APP_TZ='Asia/Dhaka';
const APP_SECRET='';
function app_secret(): string { $v=getenv('ED_SHARED_SECRET')?:APP_SECRET; if($v==='') $v='CHANGE_THIS_TO_A_LONG_RANDOM_SECRET'; return $v; }
date_default_timezone_set(APP_TZ);
function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
function base_url(string $path=''): string { return rtrim(APP_URL,'/').'/'.ltrim($path,'/'); }
function redirect_to(string $path): never { header('Location: '.base_url($path)); exit; }
