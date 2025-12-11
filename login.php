<?php
// login.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login — Welcome to Our Website</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header small">
    <div class="brand">
      <img src="assets/logo.jpg" alt="Logo" class="logo small">
      <h1>Login</h1>
    </div>
    <nav>
      <button onclick="toggleDarkMode()" class="dark-toggle">🌙</button>
      <button onclick="history.back()" class="btn small">← Back</button>
      <a href="index.php" class="btn small">Home</a>
    </nav>
  </header>

  <main class="container">
    <section class="card">
      <h2>Login</h2>
      <form id="loginForm" novalidate>
        <label>
          Name
          <input type="text" id="loginName" placeholder="Your name" required>
        </label>
        <label>
          Email
          <input type="email" id="loginEmail" placeholder="you@example.com" required>
        </label>
        <label>
          Password
          <input type="password" id="loginPassword" placeholder="password" required>
        </label>
        <div style="margin-top:12px;">
          <button type="submit" class="btn primary">Login</button>
        </div>
      </form>
    </section>
  </main>

  <div class="copyright">© Made by Ishan Chinthaka</div>
  <script src="js/app.js" defer></script>
</body>
</html>
