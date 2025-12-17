<?php
include 'config.php';

$query = $_GET['query'] ?? 'nature';
$url = PEXELS_BASE_URL . "search?query=" . urlencode($query) . "&per_page=40";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: " . PEXELS_API_KEY
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Gallery</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">
        <a href="index.php">
            <img src="logo.png" alt="Pexels Away">
        </a>
    </div>

    <div class="menu">
        <a href="index.php">Home</a>
        <a href="list.php">Gallery</a>
    </div>
</div>


<h2 class="page-title">Hasil: <?= htmlspecialchars($query) ?></h2>

<div class="gallery">
<?php if (isset($data['photos'])): ?>
    <?php foreach ($data['photos'] as $p): ?>
        <div class="card">
            <a href="detail.php?id=<?= $p['id'] ?>">
                <img src="<?= $p['src']['large'] ?>">
                <div class="overlay">
                    <div class="actions">
                        <span class="photographer"><?= htmlspecialchars($p['photographer']) ?></span>
                        <span class="btn-save">Save</span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

</body>
</html>
