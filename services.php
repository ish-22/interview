<?php
// services.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Services — Welcome to Our Website</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header small">
    <div class="brand">
      <img src="assets/logo.jpg" alt="Logo" class="logo small">
      <h1>Services</h1>
    </div>
    <nav>
      <button onclick="toggleDarkMode()" class="dark-toggle">🌙</button>
      <a href="index.php" class="btn small">← Back</a>
      <a href="index.php" class="btn small">Home</a>
    </nav>
  </header>

  <main class="container">
    <section class="services-list card">
      <h2>Services</h2>
      <ul class="services">
        <li>Web Development</li>
        <li>SEO</li>
      </ul>
      <div style="margin-top:1rem;">
        <a href="order.php" class="btn primary">Order</a>
      </div>
    </section>
  </main>

  <div class="copyright">© Made by Ishan Chinthaka</div>
  <script src="js/app.js" defer></script>
</body>
</html>
