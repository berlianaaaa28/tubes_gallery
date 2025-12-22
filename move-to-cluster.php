<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

$photoId = $data['photo_id'] ?? null;
$index   = $data['cluster_index'] ?? null;

if ($photoId === null || $index === null) {
    echo "Invalid request";
    exit;
}

if (!isset($_SESSION['clusters'][$index])) {
    echo "Cluster not found";
    exit;
}

/* Hindari duplikat */
$_SESSION['clusters'][$index]['items'] ??= [];

if (!in_array($photoId, $_SESSION['clusters'][$index]['items'])) {
    $_SESSION['clusters'][$index]['items'][] = $photoId;
}

echo "OK";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Move To Cluster</title>
</head>
<body>
<!-- Handler AJAX: move-to-cluster.php -->
</body>
</html>
