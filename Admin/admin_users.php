<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: ../admin_login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Count total students
$total_students_result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'");
$total_students = $total_students_result->fetch_assoc()['total'];

// Fetch student list
$students = $conn->query("SELECT id, name, email, created_at FROM users WHERE role='student' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #0a0f0d;
      color: #e0e0e0;
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
      box-shadow: 2px 0 10px #1fffac30;
      padding-top: 60px;
      z-index: 1000;
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
      box-shadow: 0 2px 8px #1fffac30;
      z-index: 1100;
    }

    .sidebar nav a {
      display: flex;
      align-items: center;
      padding: 12px 30px;
      color: #a3d9b0;
      font-weight: 500;
      text-decoration: none;
      transition: 0.25s ease;
    }

    .sidebar nav a i {
      margin-right: 15px;
      font-size: 18px;
      width: 20px;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
      background-color: #1fffac;
      color: #000;
      font-weight: 600;
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
      font-weight: 600;
      text-decoration: none;
    }

    .logout-btn:hover {
      background-color: #6cf0a8;
      box-shadow: 0 0 10px #82f5b4;
    }

    .main-content {
      margin-left: 230px;
      padding: 80px 30px 40px;
      min-height: 100vh;
      background: #0a0f0d;
    }

    .card-box {
      background: #101615;
      border: 1px solid #1fffac30;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 0 12px #1fffac25;
      transition: 0.3s ease;
      text-align: center;
    }

    .card-box:hover {
      box-shadow: 0 0 24px #1fffac70;
      background: #13221d;
    }

    .card-circle {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      background-color: #82f5b430;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      font-weight: bold;
      color: #82f5b4;
      margin: auto;
    }

    .card-title {
      margin-top: 15px;
      font-size: 18px;
      font-weight: 500;
      color: #82f5b4;
    }

    /* Cyber SecuraX Table Styling */
    table {
      width: 100%;
      background-color: #101615 !important;
      color: #d0fbe3;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 10px;
      overflow: hidden;
    }

    table thead {
      background: linear-gradient(90deg, #0ff, #0ff);
      color: #1fffac;
      font-weight: 900;
      font-size: 15px;
      text-transform: uppercase;
      letter-spacing: 0.9px;
      box-shadow: inset 0 -1px 0 #1fffac40;
      border-bottom: 2px solid #1fffac40;
    }

    table thead th {
      background-color: transparent !important;
      padding: 24px 26px;
      border-right: 1px solid #1fffac20;
      text-shadow: 0 0 6px #1fffac80;
    }


    table tbody tr {
      background-color: #101615 !important;
      transition: background 0.3s;
    }

    table tbody tr:hover {
      background-color: #162c24 !important;
    }

    table td {
      padding: 14px 16px;
      font-size: 15px;
      color: #d2f5e3 !important;
      background-color: #101615 !important;
      border-top: 1px solid #1fffac10;
    }

    .table-responsive {
      background-color: #000;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 20px #1fffac15;
    }


    @media (max-width: 768px) {
      .sidebar {
        width: 60px;
      }

      .sidebar .logo {
        width: 60px;
        font-size: 18px;
      }

      .sidebar nav a {
        justify-content: center;
        font-size: 0;
      }

      .sidebar nav a i {
        font-size: 20px;
      }

      .topbar {
        left: 60px;
      }

      .main-content {
        margin-left: 60px;
        padding: 80px 20px 40px;
      }
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
      <a href="admin_dashboard.php" ><i class="fas fa-tachometer-alt"></i> <span class="d-none d-md-inline">Dashboard</span></a>
      <a href="admin_users.php"class="active"><i class="fas fa-users"></i> <span class="d-none d-md-inline">Manage Users</span></a>
      <a href="create_course.php"><i class="fas fa-book-open"></i> <span class="d-none d-md-inline">Create Course</span></a>
      <a href="create_exam.php"><i class="fas fa-question-circle"></i> <span class="d-none d-md-inline">Create Exam</span></a>
      <a href="add_question.php"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add Question</span></a>
      <a href="admin_messages.php"><i class="fas fa-message"></i> <span class="d-none d-md-inline">Messages</span></a>

      <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span></a>
    </nav>
  </div>

<!-- Topbar -->
<div class="topbar">
  <h1><i class="fas fa-users-cog"></i> Admin Panel - Manage Users</h1>
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card-box">
        <div class="card-circle"><?= $total_students ?></div>
        <div class="card-title">Total Students</div>
      </div>
    </div>
  </div>

  <h4 class="text-success mb-3"><i class="fas fa-table"></i> Student List</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Registered At</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while ($row = $students->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= date('d M Y h:i A', strtotime($row['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
