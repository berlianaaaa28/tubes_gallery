<?php
session_start();

$id = $_GET['id'] ?? '';
$cluster = null;

$clusters = $_SESSION['clusters'] ?? [];

foreach ($clusters as $i => $c) {

    // ⛑️ FIX: klaster lama belum punya id
    $clusterId = $c['id'] ?? ('cluster_' . $i);

    if ($clusterId === $id) {
        $cluster = $c;
        break;
    }
}

if (!$cluster) {
    echo "Klaster tidak ditemukan";
    exit;
}

$favorites = $_SESSION['favorites'] ?? [];
$items = $cluster['items'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($cluster['name']) ?></title>
<link rel="stylesheet" href="style.css">
</head>

<body class="cosmos">

<div class="container">
    <h1><?= htmlspecialchars($cluster['name']) ?></h1>

    <?php if (empty($items)): ?>
        <p style="opacity:.6">Belum ada foto di klaster ini.</p>
    <?php else: ?>
        <div class="gallery">
            <?php foreach ($favorites as $f): ?>
                <?php if (in_array($f['id'], $items)): ?>
                    <div class="card">
                        <img src="<?= $f['src'] ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>