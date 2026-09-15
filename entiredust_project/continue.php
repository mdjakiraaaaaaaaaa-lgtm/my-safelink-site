<?php
declare(strict_types=1);
require_once __DIR__.'/config/app.php';

// No token -> back to homepage
$token = $_GET['token'] ?? '';
if ($token === '') {
    redirect_to('index.php');
}

// Validate the signed token (same scheme as api/verify.php)
$valid = false;
$raw = base64_decode(strtr($token, '-_', '+/') ?: '', true);
if ($raw !== false && str_contains($raw, '.')) {
    [$payload, $sig] = explode('.', $raw, 2);
    $data = json_decode($payload, true);
    $valid = is_array($data)
        && ($data['exp'] ?? 0) >= time()
        && hash_equals(hash_hmac('sha256', $payload, app_secret()), $sig);
}
if (!$valid) {
    redirect_to('index.php');
}

$page_title = 'Please Wait';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($page_title)?> | INFOROVA</title>
<style>
  html,body{margin:0;padding:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;}
  body.locked{overflow:hidden;}

  #adlock-overlay{
    position:fixed; inset:0; z-index:99999;
    background:rgba(10,14,20,.94);
    display:flex; align-items:center; justify-content:center;
    padding:20px;
  }
  .adlock-box{
    background:#fff; border-radius:14px; max-width:420px; width:100%;
    padding:28px 24px; text-align:center;
    box-shadow:0 20px 60px rgba(0,0,0,.4);
  }
  .adlock-title{
    font-size:20px; font-weight:800; color:#d92b2b; margin:0 0 6px;
    animation:pulse 1.2s ease-in-out infinite;
  }
  @keyframes pulse{0%,100%{opacity:1}50%{opacity:.55}}
  .adlock-sub{color:#333; font-size:15px; margin:0 0 18px;}
  .adlock-count{
    font-size:38px; font-weight:900; color:#0b63d6; margin:0 0 16px;
    font-variant-numeric:tabular-nums;
  }
  .adlock-progress{
    height:8px; width:100%; background:#e7ecf3; border-radius:6px; overflow:hidden; margin-bottom:20px;
  }
  .adlock-progress-fill{
    height:100%; width:0%; background:#0b63d6; transition:width 1s linear;
  }
  #ad-container{
    min-height:250px; background:#f3f5f8; border:1px dashed #c3cbd6; border-radius:10px;
    display:flex; align-items:center; justify-content:center; color:#8a94a3; font-size:13px;
  }

  main{max-width:520px; margin:0 auto; padding:70px 20px; text-align:center;}
  .btn{
    display:inline-block; background:#0b63d6; color:#fff; text-decoration:none;
    padding:14px 30px; border-radius:8px; font-weight:700; font-size:16px;
    border:none; cursor:pointer;
  }
  .btn:hover{background:#094fae;}
</style>
</head>
<body class="locked">

<div id="adlock-overlay">
  <div class="adlock-box">
    <p class="adlock-title">⏳ Please Wait — Preparing Your Link</p>
    <p class="adlock-sub">This page unlocks automatically in <span class="adlock-count" id="adlock-count">40</span> seconds.</p>
    <div class="adlock-progress"><div class="adlock-progress-fill" id="adlock-fill"></div></div>

    <!-- Paste your Monetag / Adsterra ad tag inside this div -->
    <div id="ad-container" aria-label="Advertisement">
      Advertisement
    </div>
  </div>
</div>

<main>
  <h1>Continue securely</h1>
  <p>Your request has been verified.</p>
  <button id="continue-btn" class="btn" style="display:none;">Click here to continue</button>
</main>

<script>
(function () {
  var TOTAL = 40;
  var seconds = TOTAL;
  var countEl = document.getElementById('adlock-count');
  var fillEl  = document.getElementById('adlock-fill');
  var overlay = document.getElementById('adlock-overlay');
  var btn     = document.getElementById('continue-btn');

  var timer = setInterval(function () {
    seconds -= 1;
    if (seconds < 0) seconds = 0;
    countEl.textContent = seconds;
    fillEl.style.width = (((TOTAL - seconds) / TOTAL) * 100) + '%';

    if (seconds <= 0) {
      clearInterval(timer);
      overlay.style.display = 'none';
      document.body.classList.remove('locked');
      btn.style.display = 'inline-block';
    }
  }, 1000);

  btn.addEventListener('click', function () {
    window.location.href = 'api/verify.php?token=<?=rawurlencode($token)?>';
  });
})();
</script>

</body>
</html>
