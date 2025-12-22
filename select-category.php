<?php
session_start();

/* ===== HANDLE SUBMIT ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['categories']) && count($_POST['categories']) === 5) {
        $_SESSION['categories'] = $_POST['categories'];
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Select Categories</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="style.css" />
<style>
body {
  margin: 0;
  background: #0e0e10;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  color: #fff;
}
.cosmos-overlay {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}
.cosmos-card {
  width: 100%;
  max-width: 1200px;
  background: #1a1a1d;
  border-radius: 24px;
  padding: 40px;
}
.cosmos-header {
  text-align: center;
  margin-bottom: 40px;
}
.cosmos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 22px;
}
.cosmos-item {
  background: #232326;
  border-radius: 18px;
  padding: 18px;
  cursor: pointer;
  position: relative;
  user-select: none;
}
.cosmos-item.selected {
  outline: 2px solid #fff;
}
.thumb-stack {
  position: relative;
  height: 120px;
  margin-bottom: 14px;
}
.thumb-stack img {
  position: absolute;
  width: 90px;
  height: 90px;
  object-fit: cover;
  border-radius: 14px;
}
.thumb-stack img:nth-child(1){left:0;top:30px}
.thumb-stack img:nth-child(2){left:50px;top:0}
.thumb-stack img:nth-child(3){left:100px;top:40px}
.add-btn {
  position: absolute;
  top: 14px;
  right: 14px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 1px solid #555;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}
.cosmos-item.selected .add-btn {
  background:#fff;
  color:#000;
}
.cosmos-footer {
  display: flex;
  justify-content: space-between;
  margin-top: 36px;
}
button {
  background:#fff;
  color:#000;
  border:none;
  padding:12px 22px;
  border-radius:999px;
  opacity:.35;
  cursor:not-allowed;
}
button.active {
  opacity:1;
  cursor:pointer;
}
</style>
</head>
<body>

<div class="cosmos-overlay">
<form class="cosmos-card" method="POST" id="categoryForm">

  <div class="cosmos-header">
    <h1>Select categories you resonate with</h1>
    <p>This will help personalize your experience</p>
  </div>

  <div class="cosmos-grid">
    <?php
    $categories = [
      'Art','Graphic Design','Fashion','Film Photography',
      'Architecture','Illustration','Interior','Typography'
    ];
    foreach ($categories as $cat):
    ?>
    <div class="cosmos-item">
      <div class="add-btn">+</div>
      <div class="thumb-stack">
        <img src="https://picsum.photos/200?<?= rand() ?>">
        <img src="https://picsum.photos/200?<?= rand() ?>">
        <img src="https://picsum.photos/200?<?= rand() ?>">
      </div>
      <h3><?= $cat ?></h3>
      <input type="checkbox" name="categories[]" value="<?= $cat ?>" hidden>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="cosmos-footer">
    <div id="counter">Pick 5 more</div>
    <button type="submit" id="continue" disabled>Continue</button>
  </div>

</form>
</div>

<script>
const items = document.querySelectorAll('.cosmos-item');
const counter = document.getElementById('counter');
const btn = document.getElementById('continue');
const form = document.getElementById('categoryForm');
const max = 5;

items.forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const checkbox = this.querySelector('input');
    const selectedCount = document.querySelectorAll('.cosmos-item.selected').length;

    if (!this.classList.contains('selected') && selectedCount >= max) return;

    this.classList.toggle('selected');
    checkbox.checked = !checkbox.checked;

    const total = document.querySelectorAll('.cosmos-item.selected').length;
    const left = max - total;

    counter.innerText = left > 0 ? `Pick ${left} more` : 'Ready to continue';

    if (total === max) {
      btn.classList.add('active');
      btn.disabled = false;
    } else {
      btn.classList.remove('active');
      btn.disabled = true;
    }
  });
});

form.addEventListener('submit', e => {
  if (document.querySelectorAll('.cosmos-item.selected').length !== 5) {
    e.preventDefault();
  }
});
</script>

</body>
</html>
