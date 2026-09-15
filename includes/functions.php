<?php
declare(strict_types=1);
require_once __DIR__.'/../config/database.php';
function setting(string $key,string $default=''): string { static $cache=[]; global $pdo; if(array_key_exists($key,$cache))return $cache[$key]; $s=$pdo->prepare('SELECT setting_value FROM settings WHERE setting_key=?');$s->execute([$key]);return $cache[$key]=(string)($s->fetchColumn()??$default); }
function slugify(string $s): string { $s=trim(mb_strtolower($s));$s=preg_replace('/[^a-z0-9\s-]/u','',$s);$s=preg_replace('/[\s-]+/','-',(string)$s);return trim((string)$s,'-')?:'post-'.time(); }
function article_url(string $slug): string { return base_url('article.php?slug='.rawurlencode($slug)); }
function ad_code(string $slot): string { $map=['top'=>setting('ad_top_code'), 'inarticle'=>setting('ad_inarticle_code'), 'bottom'=>setting('ad_bottom_code')]; return $map[$slot]??''; }
function render_ad(string $slot): void { $code=ad_code($slot); echo '<div class="ad-slot" aria-label="Advertisement">'.($code!==''?$code:'<span>Advertisement</span>').'</div>'; }
