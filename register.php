<?php
// Database credentials
$host = "localhost";
$dbname = "CyberSecuraX";
$username = "root";
$password = ""; // Update if you use a password for root

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('🎉 Registration Successful! Please login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $checkEmail->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cyber SecuraX | Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #0a0f0d;
      color: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
    }
    .auth-container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .auth-box {
      background: linear-gradient(145deg, #0f1e17, #111);
      border-radius: 12px;
      padding: 40px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 0 15px #1fffac20;
    }
    .auth-box h2 {
      text-align: center;
      color: #82f5b4;
      margin-bottom: 25px;
    }
    .form-control {
      background: #111;
      border: 1px solid #444;
      color: #fff;
    }
    .form-control:focus {
      border-color: #82f5b4;
      box-shadow: none;
    }
    .btn-glow {
      background-color: #82f5b4;
      border: none;
      color: #000;
      transition: 0.3s ease;
    }
    .btn-glow:hover {
      box-shadow: 0 0 15px #82f5b4;
      background-color: #6cf0a8;
    }
    a {
      color: #82f5b4;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="auth-container">
  <div class="auth-box">
    <h2><i class="fas fa-user-plus"></i> Register</h2>
    <form method="post" action="register_process.php">
      <div class="mb-3">
        <label>Full Name</label>
        <input type="text" class="form-control" name="name" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" required>
      </div>
      <button type="submit" class="btn btn-glow w-100 mt-3">Register</button>
      <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>
</div>

</body>
</html>
