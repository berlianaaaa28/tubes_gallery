<?php
session_start();

/* Inisialisasi */
$favorites = $_SESSION['favorites'] ?? [];
$clusters  = $_SESSION['clusters'] ?? [];

/* =============================
   HANDLE BUAT KLASTER
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cluster_name'])) {
    $name = trim($_POST['cluster_name']);

    if ($name !== '') {
        $_SESSION['clusters'][] = [
            'id'    => uniqid('cluster_'),
            'name'  => $name,
            'items' => []
        ];
    }

    header("Location: favorit.php");
    exit;
}

/* =============================
   HANDLE DRAG FOTO KE KLASTER (AJAX)
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['photo_id'], $_POST['cluster_index'])
) {
    $photoId = $_POST['photo_id'];
    $index   = (int) $_POST['cluster_index'];

    if (isset($_SESSION['clusters'][$index])) {
        if (!isset($_SESSION['clusters'][$index]['items'])) {
            $_SESSION['clusters'][$index]['items'] = [];
        }

        if (!in_array($photoId, $_SESSION['clusters'][$index]['items'])) {
            $_SESSION['clusters'][$index]['items'][] = $photoId;
        }
    }

    exit; // PENTING: hanya untuk AJAX
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Favorit</title>
<link rel="stylesheet" href="style.css">

<style>
.fav-container { padding: 40px; }

.fav-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-cluster {
    background: #fff;
    color: #000;
    border: none;
    padding: 10px 18px;
    border-radius: 999px;
    font-weight: bold;
    cursor: pointer;
}

/* GRID FOTO */
.fav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
    gap: 18px;
    margin-top: 30px;
}

.fav-card {
    border-radius: 16px;
    overflow: hidden;
    cursor: grab;
}

.fav-card img {
    width: 100%;
    display: block;
}

/* KLASTER */
.cluster-row {
    display: flex;
    gap: 16px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.cluster-box {
    padding: 16px;
    border-radius: 14px;
    background: #1a1a1a;
    min-width: 180px;
    color: #fff;
    cursor: pointer;
}
.cluster-box:hover {
    background: #222;
}

/* MODAL */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    display: none;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #111;
    padding: 24px;
    border-radius: 16px;
    width: 320px;
}

.modal-content input {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: none;
    margin-bottom: 14px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.modal-actions button {
    padding: 8px 14px;
    border-radius: 999px;
    border: none;
    cursor: pointer;
}
</style>
</head>

<body class="cosmos">

<div class="fav-container">

    <div class="fav-header">
        <h1>Favorit Kamu</h1>
        <button class="btn-cluster" onclick="openModal()">Buat Klaster</button>
    </div>

    <?php if (empty($favorites)): ?>
        <p style="opacity:.6;margin-top:12px">Belum ada foto favorit.</p>
    <?php else: ?>
        <p style="opacity:.6;margin-top:12px">🔗 Drag foto favorit ke klaster</p>

        <div class="fav-grid">
            <?php foreach ($favorites as $f): ?>
                <div class="fav-card"
                     draggable="true"
                     ondragstart="dragStart(event)"
                     data-id="<?= $f['id'] ?>">
                    <img src="<?= $f['src'] ?>">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($clusters)): ?>
        <h3 style="margin-top:40px">🗂️ Klaster → buka halaman detail</h3>

        <div class="cluster-row">
            <?php foreach ($clusters as $i => $c): 
                // ⛑️ FIX: klaster lama belum punya id
                $clusterId = $c['id'] ?? ('cluster_' . $i);
                $items = $c['items'] ?? [];
?>
                <div class="cluster-box"
                    onclick="openCluster('<?= $clusterId ?>')"
                    ondragover="allowDrop(event)"
                    ondrop="dropToCluster(event, <?= $i ?>)">
                    <strong><?= htmlspecialchars($c['name']) ?></strong><br>
                    <small><?= count($items) ?> foto</small>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>

<!-- MODAL -->
<div class="modal" id="clusterModal">
    <form method="POST" class="modal-content">
        <h3>Buat Klaster</h3>
        <input type="text" name="cluster_name" placeholder="Nama klaster..." required>

        <div class="modal-actions">
            <button type="button" onclick="closeModal()">Batal</button>
            <button type="submit">Simpan</button>
        </div>
    </form>
</div>

<script>
let draggedId = null;

function dragStart(e) {
    draggedId = e.target.closest('.fav-card').dataset.id;
}

function allowDrop(e) {
    e.preventDefault();
}

function dropToCluster(e, index) {
    e.preventDefault();

    fetch('favorit.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'photo_id=' + draggedId + '&cluster_index=' + index
    }).then(() => location.reload());
}

function openModal() {
    document.getElementById('clusterModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('clusterModal').style.display = 'none';
}

/* === INI YANG KEMARIN KURANG === */
function openCluster(id) {
    window.location.href = 'cluster.php?id=' + id;
}
</script>

</body>
</html>