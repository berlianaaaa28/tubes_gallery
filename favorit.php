<?php
// favorite.php - halaman gallery foto favorit (menggunakan localStorage)
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Favorite Gallery</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .photo-card {
      background: #fff;
      border-radius: 12px;
      padding: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .photo-card img {
      width: 100%;
      border-radius: 10px;
    }
    .remove-btn {
      margin-top: 8px;
      background: #ff4d6d;
      border: none;
      padding: 6px 12px;
      color: #fff;
      border-radius: 8px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h2>❤️ Favorite Photos</h2>
<p>Berisi foto-foto yang telah kamu tandai sebagai favorit.</p>

<div class="gallery" id="favoriteGallery"></div>

<script>
  let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
  const gallery = document.getElementById('favoriteGallery');

  if (favorites.length === 0) {
    gallery.innerHTML = '<p>Belum ada foto favorit.</p>';
  }

  favorites.forEach((photo, index) => {
    gallery.innerHTML += `
      <div class="photo-card">
        <img src="${photo.img}" alt="favorite">
        <p>${photo.photographer}</p>
        <button class="remove-btn" onclick="removeFavorite(${index})">Hapus</button>
      </div>
    `;
  });

  function removeFavorite(index) {
    favorites.splice(index, 1);
    localStorage.setItem('favorites', JSON.stringify(favorites));
    location.reload();
  }
</script>

</body>
</html>
