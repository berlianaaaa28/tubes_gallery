<?php
include 'config.php';

$ideas = [
    'Vision Board 2026' => 'vision',
    'Destinasi Wisata'  => 'travel',
    'Creative Desk'    => 'workspace',
    'Sunset Mood'      => 'sunset'
];

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
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pexels Away</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===== NAVBAR (SATU SAJA) ===== -->
<div class="navbar">
    <div class="logo">
        <a href="index.php">
            <img src="logo.png" alt="Pexels Away">
        </a>
    </div>

    <div class="nav-right">
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="list.php">Gallery</a></li>
        </ul>

        <!-- Dark Mode Toggle -->
        <div class="theme-switch">
            <input type="checkbox" id="darkToggle" hidden>
            <label for="darkToggle" class="switch">
                <span class="sun">☀️</span>
                <span class="moon">🌙</span>
            </label>
        </div>
    </div>
</div>

<h1 class="page-title">Pinterest-style Photo Gallery</h1>

<div style="text-align:center;">
    <form action="list.php" method="get">
        <input type="text" name="query" placeholder="Cari foto...">
        <button type="submit">Search</button>
    </form>
</div>

<!-- IDEAS -->
<div class="ideas-section">
    <h2>Ideas you might like</h2>
    <div class="ideas-row">
        <?php foreach ($ideas as $title => $query): ?>
            <a href="list.php?query=<?= $query ?>" class="idea-card">
                <div class="overlay"><span>View Gallery</span></div>
                <img src="<?= getIdeaImage($query) ?>" alt="<?= $title ?>">
                <div class="idea-info">
                    <h4><?= $title ?></h4>
                    <p><?= ucfirst($query) ?> inspiration</p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<script>
const toggle = document.getElementById("darkToggle");

// load theme
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark");
    toggle.checked = true;
}

toggle.addEventListener("change", function () {
    document.body.classList.toggle("dark");
    localStorage.setItem("theme", this.checked ? "dark" : "light");
});
</script>

</body>
</html>
