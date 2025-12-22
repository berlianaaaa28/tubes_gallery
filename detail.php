<?php
session_start();
include 'config.php';

$id = $_GET['id'] ?? '';
if (!$id) die('Foto tidak ditemukan');

/* INIT SESSION */
$_SESSION['favorites'] ??= [];
$_SESSION['clusters']  ??= [];

/* AMBIL FOTO DARI PEXELS */
$url = PEXELS_BASE_URL . "photos/" . $id;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: " . PEXELS_API_KEY
]);
$response = curl_exec($ch);
curl_close($ch);

$p = json_decode($response, true);
if (!$p) die('Foto tidak ditemukan');

/* =============================
   HANDLE FAVORIT
============================= */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='favorite') {

    foreach ($_SESSION['favorites'] as $f) {
        if ($f['id'] === $p['id']) exit;
    }

    $_SESSION['favorites'][] = [
        'id'  => $p['id'],
        'src' => $p['src']['large2x']
    ];
    exit;
}

/* =============================
   HANDLE MASUK KLASTER
============================= */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='cluster') {
    $index = (int)$_POST['cluster_index'];

    if (isset($_SESSION['clusters'][$index])) {
        $_SESSION['clusters'][$index]['items'] ??= [];

        if (!in_array($p['id'], $_SESSION['clusters'][$index]['items'])) {
            $_SESSION['clusters'][$index]['items'][] = $p['id'];
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail</title>
<link rel="stylesheet" href="style.css">

<style>
.detail-actions{
    margin:16px 0;
    display:flex;
    gap:12px;
}
.detail-actions button{
    padding:10px 18px;
    border-radius:999px;
    border:none;
    cursor:pointer;
    font-weight:bold;
}

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
    color:#fff;
    padding:20px;
    border-radius:16px;
    width:300px;
}
.cluster-item{
    background:#1a1a1a;
    padding:10px;
    border-radius:10px;
    margin-bottom:8px;
    cursor:pointer;
}
.cluster-item:hover{
    background:#2a2a2a;
}
</style>
</head>

<body class="cosmos">

<!-- ✅ NAVBAR KONSISTEN -->
<header class="topbar">
    <div class="left">
        <img src="logo.png" class="logo" alt="Logo">
        <span class="discover">DISCOVER</span>
    </div>

    <div class="right">
        <a href="index.php">HOME</a>
        <a href="list.php">GALLERY</a>
        <a href="trending.php">TRENDING</a>
        <a href="favorit.php">❤️</a>
    </div>
</header>

<div class="detail-container">
    <a class="back-link" href="javascript:history.back()">⬅ Kembali</a>

    <img src="<?= $p['src']['large2x'] ?>">

    <!-- 🔥 ACTION BUTTON -->
    <div class="detail-actions">
        <button onclick="saveFavorite()">⭐ Favorit</button>
        <button onclick="openCluster()">🗂️ Masuk Klaster</button>
    </div>

    <p><b>Photographer:</b> <?= htmlspecialchars($p['photographer']) ?></p>
    <p>
        <a href="<?= $p['url'] ?>" target="_blank">View on Pexels</a>
    </p>
</div>

<!-- MODAL PILIH KLASTER -->
<div class="modal" id="clusterModal">
    <div class="modal-content">
        <h3>Pilih Klaster</h3>

        <?php if (empty($_SESSION['clusters'])): ?>
            <p style="opacity:.6">Belum ada klaster</p>
        <?php else: ?>
            <?php foreach ($_SESSION['clusters'] as $i=>$c): ?>
                <div class="cluster-item"
                     onclick="addToCluster(<?= $i ?>)">
                    <?= htmlspecialchars($c['name']) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <button style="margin-top:10px" onclick="closeCluster()">Tutup</button>
    </div>
</div>

<script>
function saveFavorite(){
    fetch('detail.php?id=<?= $p['id'] ?>',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'action=favorite'
    }).then(()=>alert('Masuk favorit ❤️'));
}

function openCluster(){
    document.getElementById('clusterModal').style.display='flex';
}
function closeCluster(){
    document.getElementById('clusterModal').style.display='none';
}

function addToCluster(index){
    fetch('detail.php?id=<?= $p['id'] ?>',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'action=cluster&cluster_index='+index
    }).then(()=>{
        alert('Masuk klaster 🗂️');
        closeCluster();
    });
}
</script>

</body>
</html>