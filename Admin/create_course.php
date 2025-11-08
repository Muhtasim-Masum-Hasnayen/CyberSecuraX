<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $admin_id = $_SESSION["admin_id"];

    $stmt = $conn->prepare("INSERT INTO courses (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $admin_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Course created successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to create course.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Course - Cyber SecuraX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: #0a0f0d;
      color: #eee;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 230px;
      background-color: #0f1e17;
      padding-top: 60px;
      z-index: 1000;
      box-shadow: 2px 0 10px #1fffac30;
    }

    .sidebar .logo {
      position: fixed;
      top: 0;
      left: 0;
      height: 60px;
      width: 230px;
      background: #082a1c;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #82f5b4;
      font-weight: 700;
      font-size: 20px;
      z-index: 1100;
      box-shadow: 0 2px 8px #1fffac30;
    }

    .sidebar nav a {
      display: flex;
      align-items: center;
      padding: 12px 30px;
      color: #a3d9b0;
      text-decoration: none;
      transition: 0.25s;
    }

    .sidebar nav a i {
      margin-right: 15px;
      font-size: 18px;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
      background-color: #1fffac;
      color: #000;
      font-weight: bold;
      border-radius: 6px;
      box-shadow: 0 0 10px #1fffacaa;
    }

    .topbar {
      position: fixed;
      top: 0;
      left: 230px;
      right: 0;
      height: 60px;
      background: #0f1e17;
      padding: 0 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px #1fffac30;
      z-index: 1050;
    }

    .topbar h1 {
      color: #82f5b4;
      font-size: 22px;
      margin: 0;
    }

    .logout-btn {
      background-color: #82f5b4;
      color: #000;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }

    .logout-btn:hover {
      background-color: #6cf0a8;
      box-shadow: 0 0 10px #82f5b4;
    }

    .main-content {
      margin-left: 230px;
      padding: 80px 30px;
    }

    .form-control, .btn {
      background-color: #111;
      color: #82f5b4;
      border: 1px solid #444;
    }

    .btn:hover {
      background-color: #82f5b4;
      color: #000;
    }

    @media (max-width: 768px) {
      .sidebar { width: 60px; }
      .sidebar .logo { width: 60px; font-size: 16px; }
      .sidebar nav a { justify-content: center; font-size: 0; }
      .sidebar nav a i { margin: 0; font-size: 20px; }
      .topbar { left: 60px; }
      .main-content { margin-left: 60px; padding: 80px 20px; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
      <i class="fas fa-shield-alt"></i>&nbsp;<span class="d-none d-md-inline">CyberSecuraX</span>
    </div>
    <nav>
      <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span class="d-none d-md-inline">Dashboard</span></a>
      <a href="admin_users.php"><i class="fas fa-users"></i> <span class="d-none d-md-inline">Manage Users</span></a>
      <a href="create_course.php"class="active"><i class="fas fa-book-open"></i> <span class="d-none d-md-inline">Create Course</span></a>
      <a href="create_exam.php"><i class="fas fa-question-circle"></i> <span class="d-none d-md-inline">Create Exam</span></a>
      <a href="add_question.php"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add Question</span></a>
      <a href="admin_messages.php"><i class="fas fa-message"></i> <span class="d-none d-md-inline">Messages</span></a>

      <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span></a>
    </nav>
  </div>

<!-- Topbar -->
<div class="topbar">
  <h1><i class="fas fa-shield-alt"></i> Admin Panel - Cyber SecuraX</h1>
  <a href="../logout.php" class="logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="container">
    <h2 class="text-center text-success mb-4">📚 Create a New Course</h2>
    <form method="POST">
      <div class="mb-3">
        <label>Course Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn w-100">➕ Create Course</button>
    </form>
  </div>
</div>

</body>
</html>
