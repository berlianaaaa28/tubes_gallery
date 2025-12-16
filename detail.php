<?php
include 'config.php';
$id = $_GET['id'];

$url = PEXELS_BASE_URL . "photos/" . $id;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: " . PEXELS_API_KEY
]);
$response = curl_exec($ch);
curl_close($ch);

$p = json_decode($response, true);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Detail</title>
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


<div class="detail-container">
    <a class="back-link" href="javascript:history.back()">⬅ Kembali</a>
    <img src="<?= $p['src']['large2x'] ?>">
    <p><b>Photographer:</b> <?= htmlspecialchars($p['photographer']) ?></p>
    <p>
        <a href="<?= $p['url'] ?>" target="_blank">View on Pexels</a>
    </p>
</div>

</body>
</html>