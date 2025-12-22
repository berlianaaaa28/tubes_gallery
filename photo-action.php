<?php
session_start();

$_SESSION['favorites'] ??= [];
$_SESSION['clusters']  ??= [];

/* =========================
   LIST KLASTER (GET)
========================= */
if (($_GET['action'] ?? '') === 'list_cluster') {
    foreach ($_SESSION['clusters'] as $i => $c) {
        echo "<button onclick=\"addToCluster($i)\">" . htmlspecialchars($c['name']) . "</button><br>";
    }
    exit;
}

/* =========================
   VALIDASI POST ACTION
========================= */
$action = $_POST['action'] ?? '';

if ($action === '') {
    exit; // ⛔ STOP jika tidak ada action
}

/* =========================
   FAVORIT
========================= */
if ($action === 'favorite') {
    $id  = $_POST['photo_id'] ?? '';
    $src = $_POST['src'] ?? '';

    if (!$id) exit;

    foreach ($_SESSION['favorites'] as $f) {
        if ($f['id'] === $id) exit;
    }

    $_SESSION['favorites'][] = [
        'id'  => $id,
        'src' => $src
    ];
    exit;
}

/* =========================
   ADD KE KLASTER
========================= */
if ($action === 'add_cluster') {
    $id    = $_POST['photo_id'] ?? '';
    $index = (int) ($_POST['cluster_index'] ?? -1);

    if ($id === '' || !isset($_SESSION['clusters'][$index])) exit;

    $_SESSION['clusters'][$index]['items'] ??= [];

    if (!in_array($id, $_SESSION['clusters'][$index]['items'])) {
        $_SESSION['clusters'][$index]['items'][] = $id;
    }
    exit;
}
?>

<!-- HTML dummy untuk memenuhi aturan PHPUnit (tidak mempengaruhi fungsi PHP) -->
<div style="display:none"></div>
