<?php
// order.php
// If AJAX POST, process request and return JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    // read raw POST body (we expect JSON)
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $name = trim($input['name'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $email = trim($input['email'] ?? '');
    $bill_by_someone_else = !empty($input['bill_by_someone_else']) ? 1 : 0;
    $created_at = date('Y-m-d H:i:s');

    // server-side validation
    if ($name === '' || $phone === '') {
        echo json_encode(['success' => false, 'message' => 'Name and phone are required.']);
        exit;
    }
    if (!preg_match('/^[0-9]{7,15}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Phone must be digits only (7-15 digits).']);
        exit;
    }
    if (!$bill_by_someone_else && $email === '') {
        echo json_encode(['success' => false, 'message' => 'Email is required when the bill is not made by someone else.']);
        exit;
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    // save to DB
    require_once __DIR__ . '/db/connect.php';
    $stmt = $mysqli->prepare("INSERT INTO orders (name, phone, email, bill_by_someone_else, created_at) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
        exit;
    }
    $stmt->bind_param('sssis', $name, $phone, $email, $bill_by_someone_else, $created_at);
    $ok = $stmt->execute();
    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $stmt->error]);
        exit;
    }
    $insertedId = $stmt->insert_id;
    $stmt->close();
    echo json_encode(['success' => true, 'id' => $insertedId, 'message' => 'Order saved.']);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Services — Order</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header small">
    <div class="brand">
      <img src="assets/logo.jpg" alt="Logo" class="logo small">
      <h1>Order</h1>
    </div>
    <nav>
      <button onclick="toggleDarkMode()" class="dark-toggle">🌙</button>
      <button onclick="history.back()" class="btn small">← Back</button>
      <a href="services.php" class="btn small">Services</a>
    </nav>
  </header>

  <main class="container">
    <!-- Home preview section (requirement: when Order clicked, show home with name/phone/email if available) -->
    <section id="homePreview" class="card hidden">
      <h3>Home (Your contact)</h3>
      <p><strong>Name:</strong> <span id="hpName">—</span></p>
      <p><strong>Phone:</strong> <span id="hpPhone">—</span></p>
      <p><strong>Email:</strong> <span id="hpEmail">—</span></p>
    </section>

    <section class="card">
      <h2>Order</h2>
      <form id="orderForm" novalidate>
        <label>
          Name
          <input type="text" id="name" name="name" required placeholder="Your full name">
        </label>

        <label>
          Phone (digits only)
          <input type="tel" id="phone" name="phone" required placeholder="e.g. 770123456">
        </label>

        <label>
          Email
          <input type="email" id="email" name="email" placeholder="you@example.com">
        </label>

        <label class="checkbox">
          <input type="checkbox" id="billBySomeoneElse" name="bill_by_someone_else">
          Bill made by someone else
        </label>

        <div style="display:flex; gap:8px; margin-top:12px;">
          <button class="btn primary" type="submit">Submit</button>
          <button class="btn" type="button" id="clearBtn">Clear</button>
        </div>
      </form>
    </section>
  </main>

  <!-- Success popup -->
  <div id="popup" class="popup hidden">
    <div class="popup-card">
      <p id="popupMsg">Submit Success</p>
      <button id="popupClose" class="btn" onclick="window.location.href='services.php'">OK</button>
    </div>
  </div>

  <div class="copyright">© Made by Ishan Chinthaka</div>
  <script src="js/app.js" defer></script>
</body>
</html>
