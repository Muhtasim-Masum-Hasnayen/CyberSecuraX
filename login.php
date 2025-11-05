<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cyber SecuraX | Login</title>
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
      max-width: 400px;
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
    <h2><i class="fas fa-shield-alt"></i> Login</h2>
    <form method="post" action="login_process.php">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button type="submit" class="btn btn-glow w-100 mt-3">Login</button>
      <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
    </form>
  </div>
</div>

</body>
</html>
