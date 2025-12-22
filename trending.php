<?php
session_start();
include 'config.php';

/* ===== AMBIL FOTO TRENDING DARI PEXELS ===== */
function getTrendingPhotos($limit = 30) {
    $url = "https://api.pexels.com/v1/curated?per_page=$limit";

    $opts = [
        "http" => [
            "method"  => "GET",
            "header"  => "Authorization: " . PEXELS_API_KEY
        ]
    ];

    $context = stream_context_create($opts);
    $json = @file_get_contents($url, false, $context);
    $data = json_decode($json, true);

    return $data['photos'] ?? [];
}

$photos = getTrendingPhotos();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Trending</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="style.css">

<style>
.trending-header {
    padding: 20px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
}

.grid {
    column-count: 4;
    column-gap: 16px;
    padding: 16px;
}

@media (max-width: 1200px) { .grid { column-count: 3; } }
@media (max-width: 768px)  { .grid { column-count: 2; } }
@media (max-width: 480px)  { .grid { column-count: 1; } }

.pin {
    break-inside: avoid;
    margin-bottom: 16px;
    border-radius: 12px;
    overflow: hidden;
    background: #111;
    position: relative;
    cursor: pointer;
}

.pin img {
    width: 100%;
    display: block;
}

.badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #e60023;
    color: #fff;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    z-index: 2;
}

.pin-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.35);
    opacity: 0;
    transition: 0.3s;
    display: flex;
    align-items: flex-end;
    padding: 12px;
    color: #fff;
}

.pin:hover .pin-overlay {
    opacity: 1;
}

.pin-title {
    font-weight: bold;
    font-size: 14px;
}
</style>
</head>

<body class="cosmos">

<!-- NAVBAR (SAMA KAYA HALAMAN LAIN) -->
<header class="topbar">
    <div class="left">
        <img src="logo.png" class="logo">
        <span class="discover">DISCOVER</span>
    </div>

    <div class="right">
        <a href="index.php">HOME</a>
        <a href="list.php">GALLERY</a>
        <a href="trending.php" class="active">TRENDING</a>
        <a href="favorit.php">FAVORIT</a>
    </div>
</header>

<div class="trending-header">🔥 Trending Now</div>

<div class="grid">
<?php if (!empty($photos)): ?>
    <?php foreach ($photos as $p): ?>
        <div class="pin" onclick="location.href='detail.php?id=<?= $p['id'] ?>'">
            <span class="badge">Trending</span>
            <img src="<?= $p['src']['large'] ?>" alt="Trending Photo">
            <div class="pin-overlay">
                <div class="pin-title"><?= htmlspecialchars($p['photographer']) ?></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align:center;opacity:.6">Tidak ada konten trending</p>
<?php endif; ?>
</div>

</body>
</html>