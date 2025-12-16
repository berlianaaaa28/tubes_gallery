<?php
include 'config.php';

/* Data ide */
$ideas = [
    'Vision Board 2026' => 'vision',
    'Destinasi Wisata'  => 'travel',
    'Creative Desk'    => 'workspace',
    'Sunset Mood'      => 'sunset'
];

/* Ambil 1 foto dari Pexels */
function getIdeaImage($query) {
    $url = "https://api.pexels.com/v1/search?query=$query&per_page=1";
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Authorization: " . PEXELS_API_KEY
        ]
    ];
    $context = stream_context_create($opts);
    $json = file_get_contents($url, false, $context);
    $data = json_decode($json, true);

    return $data['photos'][0]['src']['large'] ?? '';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Pexels Gallery</title>
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

<h1 class="page-title">Pinterest-style Photo Gallery</h1>

<div style="text-align:center;">
    <form action="list.php" method="get">
        <input type="text" name="query" placeholder="Cari foto..." 
               style="padding:12px;width:250px;border-radius:10px;border:1px solid #ccc;">
        <button type="submit"
                style="padding:12px 18px;border-radius:10px;border:none;background:#1E88E5;color:white;">
            Search
        </button>
    </form>
</div>

<!-- IDEAS YOU MIGHT LIKE (DINAMIS DARI API PEXELS) -->
<div class="ideas-section">
    <h2>Ideas you might like</h2>

    <div class="ideas-row">
        <?php foreach ($ideas as $title => $query): ?>
            <a href="list.php?query=<?= $query ?>" class="idea-card">
                <div class="overlay">
                    <span>View Gallery</span>
                </div>
                <img src="<?= getIdeaImage($query) ?>" alt="<?= $title ?>">
                <div class="idea-info">
                    <h4><?= $title ?></h4>
                    <p><?= ucfirst($query) ?> inspiration</p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
