<?php
session_start();
include 'config.php';

/* ===== ONBOARDING CHECK ===== */
if (!isset($_SESSION['categories']) || empty($_SESSION['categories'])) {
    header("Location: select-category.php");
    exit;
}

/* ===== INIT FAVORITES ===== */
$_SESSION['favorites'] ??= [];

/* ===== AMBIL FOTO DARI PEXELS ===== */
function getPhotos($query, $limit = 8) {
    $url = "https://api.pexels.com/v1/search?query=" . urlencode($query) . "&per_page=$limit";
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Authorization: " . PEXELS_API_KEY
        ]
    ];
    $context = stream_context_create($opts);
    $json = @file_get_contents($url, false, $context);
    $data = json_decode($json, true);
    return $data['photos'] ?? [];
}

/* ===== HANDLE FAVORIT (AJAX) ===== */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='favorite') {
    $id  = $_POST['id'];
    $src = $_POST['src'];

    foreach ($_SESSION['favorites'] as $f) {
        if ($f['id']===$id) exit;
    }

    $_SESSION['favorites'][] = [
        'id'  => $id,
        'src' => $src
    ];
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Discover • Gallery</title>
<link rel="stylesheet" href="style.css">

<style>
/* MODAL */
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.6);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:999;
}
.modal-content{
    background:#111;
    padding:20px;
    border-radius:16px;
    width:320px;
}
.modal-content img{
    width:100%;
    border-radius:12px;
}
.modal-actions{
    margin-top:14px;
    display:flex;
    gap:10px;
}
.modal-actions button{
    flex:1;
    padding:8px;
    border-radius:999px;
    border:none;
    cursor:pointer;
}
</style>
</head>

<body class="cosmos">

<!-- ===== TOPBAR ===== -->
<header class="topbar">
    <div class="left">
        <img src="logo.png" class="logo">
        <span class="discover">DISCOVER</span>
    </div>

    <form action="list.php" method="get" class="search">
        <input type="text" name="query" placeholder="Try 'archival animations'">
    </form>

    <div class="right">
        <a href="index.php" class="active">HOME</a>
        <a href="list.php">GALLERY</a>
        <a href="trending.php">TRENDING</a>
        <a href="favorit.php">FAVORIT</a>
    </div>
</header>

<!-- ===== CATEGORY ===== -->
<div class="tabs">
    <span class="active">Unggulan</span>
    <?php foreach ($_SESSION['categories'] as $cat): ?>
        <span onclick="location.href='list.php?query=<?= urlencode($cat) ?>'">
            <?= htmlspecialchars(ucfirst($cat)) ?>
        </span>
    <?php endforeach; ?>
</div>

<!-- ===== CONTENT ===== -->
<div class="container">
<?php foreach ($_SESSION['categories'] as $category): ?>
    <h2 class="section-title">Dipilih untuk <?= htmlspecialchars(ucfirst($category)) ?></h2>

    <div class="card-row">
        <?php foreach (getPhotos($category) as $photo): 
            $pid = $photo['id'];
            $src = $photo['src']['large'];
        ?>
            <div class="card"
                 onclick="openModal('<?= $pid ?>','<?= $src ?>')">
                <img src="<?= $src ?>">
                <div class="meta">
                    <span><?= htmlspecialchars($photo['photographer']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
</div>

<!-- ===== MODAL FOTO ===== -->
<div class="modal" id="photoModal">
    <div class="modal-content">
        <img id="modalImg">
        <div class="modal-actions">
            <button onclick="saveFavorite()">⭐ Favorit</button>
            <button onclick="closeModal()">✖</button>
        </div>
    </div>
</div>

<script>
let currentId = null;
let currentSrc = null;

function openModal(id, src){
    currentId = id;
    currentSrc = src;
    document.getElementById('modalImg').src = src;
    document.getElementById('photoModal').style.display='flex';
}

function closeModal(){
    document.getElementById('photoModal').style.display='none';
}

function saveFavorite(){
    fetch('index.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'action=favorite&id='+currentId+'&src='+encodeURIComponent(currentSrc)
    }).then(()=>{
        alert('Masuk favorit ❤️');
        closeModal();
    });
}
</script>

</body>
</html>
