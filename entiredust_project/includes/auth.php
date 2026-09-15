<?php
declare(strict_types=1);
require_once __DIR__.'/../config/database.php';
if(session_status()!==PHP_SESSION_ACTIVE){session_name('ed_session');session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Lax','path'=>'/']);session_start();}
function csrf(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(24)); return $_SESSION['csrf']; }
function check_csrf(): void { if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(419);exit('Invalid request.');} }
function admin_user(): ?array { if(empty($_SESSION['admin_id']))return null; $s=$GLOBALS['pdo']->prepare('SELECT * FROM admins WHERE id=? AND status=\'active\'');$s->execute([(int)$_SESSION['admin_id']]);return $s->fetch()?:null; }
function require_admin(): array { $u=admin_user(); if(!$u) redirect_to('admin/login.php'); return $u; }
