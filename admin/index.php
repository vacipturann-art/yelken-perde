<?php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store');
$v = @filemtime(__DIR__ . '/admin.js') . '-' . @filemtime(__DIR__ . '/admin.css');
?><!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Yönetim Paneli — Yelken Perde</title>
<link rel="icon" type="image/png" href="../images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css?v=<?= htmlspecialchars($v) ?>">
</head>
<body>
<div id="app"><div class="boot">Yükleniyor…</div></div>
<div id="toasts" aria-live="polite"></div>
<noscript><p class="boot">Panel için tarayıcınızda JavaScript açık olmalıdır.</p></noscript>
<script src="admin.js?v=<?= htmlspecialchars($v) ?>"></script>
</body>
</html>
