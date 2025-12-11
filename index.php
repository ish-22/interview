<?php
// index.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Welcome to Our Website</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="brand">
      <img src="assets/logo.jpg" alt="Logo" class="logo">
      <h1>Welcome to Our Website</h1>
    </div>
    <nav>
      <button onclick="toggleDarkMode()" class="dark-toggle">🌙</button>
      <button onclick="history.back()" class="btn small">← Back</button>
      <a href="login.php" class="btn small">Login</a>
    </nav>
  </header>

  <main class="container">
    <section class="services-head">
      <h2>Services</h2>
      <a href="services.php" class="btn">Lift</a>
    </section>

    <section class="intro-card">
      <h3>Why choose us</h3>
      <p>Modern UI/UX, responsive design, and clean, well-documented code — perfect for interviews.</p>
    </section>
    
    <section class="intro-card" style="margin-top: 2rem;">
      <h3>Quick Access</h3>
      <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1rem;">
        <a href="services.php" class="btn">Services</a>
        <a href="login.php" class="btn">Login</a>
      </div>
    </section>
  </main>

  <div class="copyright">© Made by Ishan Chinthaka</div>
  <script src="js/app.js" defer></script>
</body>
</html>
