<?php
$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) die("Connection failed.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $contact_info = trim($_POST['contact_info']);
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);

  $stmt = $conn->prepare("INSERT INTO contact_messages (name, contact_info, subject, message) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $name, $contact_info, $subject, $message);

  if ($stmt->execute()) {
    $success = "✅ Message sent successfully!";
  } else {
    $error = "❌ Failed to send message. Try again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Cyber SecuraX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #0a0f0d;
      color: #eee;
      font-family: 'Segoe UI', sans-serif;
    }
    .topbar {
      background: #082a1c;
      color: #82f5b4;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 6px #1fffac40;
    }
    .topbar .nav-link {
      color: #82f5b4;
      margin-left: 20px;
      font-weight: 500;
      text-decoration: none;
    }
    .topbar .nav-link:hover {
      color: #1fffac;
      text-shadow: 0 0 5px #1fffac;
    }

    .container {
      margin-top: 60px;
      max-width: 700px;
      background: #111;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px #1fffac30;
    }

    .form-control, .btn {
      background-color: #0f1e17;
      color: #82f5b4;
      border: 1px solid #444;
    }
    .form-control::placeholder {
      color: #999;
    }
    .btn:hover {
      background-color: #82f5b4;
      color: #000;
    }
    .form-title {
      color: #1fffac;
    }
  </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
  <h3 class="m-0"><i class="fas fa-shield-alt"></i> Cyber SecuraX</h3>
  <div>
    <a href="landing.php" class="nav-link">Home</a>

  </div>
</div>

<!-- Contact Form -->
<div class="container mt-5">
  <h2 class="text-center form-title mb-4">📬 Contact Us</h2>

  <?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" placeholder="Your full name" required>
    </div>
    <div class="mb-3">
      <label>Contact Info (Email or Phone)</label>
      <input type="text" name="contact_info" class="form-control" placeholder="example@mail.com or 01XXXXXXXXX" required>
    </div>
    <div class="mb-3">
      <label>Subject</label>
      <input type="text" name="subject" class="form-control" placeholder="Subject of your message" required>
    </div>
    <div class="mb-3">
      <label>Message</label>
      <textarea name="message" class="form-control" rows="5" placeholder="Write your message here..." required></textarea>
    </div>
    <button type="submit" class="btn w-100">📨 Send Message</button>
  </form>
</div>

<!-- FontAwesome (for icons) -->
<script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
</body>
</html>
