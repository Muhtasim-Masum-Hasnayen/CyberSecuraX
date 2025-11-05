<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) die("DB connection failed.");

// Fetch contact messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin | Contact Messages</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      background: #0a0f0d;
      color: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 230px;
      background-color: #0f1e17;
      box-shadow: 2px 0 10px #1fffac30;
      display: flex;
      flex-direction: column;
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
      user-select: none;
      z-index: 1100;
    }

    .sidebar nav a {
      display: flex;
      align-items: center;
      padding: 12px 30px;
      color: #a3d9b0;
      text-decoration: none;
      font-size: 15px;
      transition: background-color 0.25s, color 0.25s;
    }

    .sidebar nav a i {
      margin-right: 15px;
      font-size: 18px;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
      background-color: #1fffac;
      color: #000;
      font-weight: 600;
      border-radius: 6px;
      box-shadow: 0 0 8px #1fffacaa;
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
    }

    .table {
      background-color: #111;
      color: #eee;
      border-radius: 8px;
      overflow: hidden;
    }

    .table th {
      background-color: #1a1a1a;
      color: #82f5b4;
    }

    .table td {
      vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #1c1c1c;
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
        padding: 12px 10px;
        font-size: 0;
        justify-content: center;
      }
      .sidebar nav a i {
        margin: 0;
        font-size: 20px;
      }
      .topbar {
        left: 60px;
      }
      .main-content {
        margin-left: 60px;
        padding: 80px 15px 40px;
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
      <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span class="d-none d-md-inline">Dashboard</span></a>
      <a href="admin_users.php"><i class="fas fa-users"></i> <span class="d-none d-md-inline">Manage Users</span></a>
      <a href="create_course.php"><i class="fas fa-book-open"></i> <span class="d-none d-md-inline">Create Course</span></a>
      <a href="create_exam.php"><i class="fas fa-question-circle"></i> <span class="d-none d-md-inline">Create Exam</span></a>
      <a href="add_question.php"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add Question</span></a>
      <a href="admin_messages.php"  class="active"><i class="fas fa-message"></i> <span class="d-none d-md-inline">Messages</span></a>

      <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span></a>
    </nav>
  </div>

<!-- Topbar -->
<div class="topbar">
  <h1><i class="fas fa-shield-alt"></i> Admin Panel - Contact Messages</h1>
  <a href="../logout.php" class="logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <h3>📬 Contact Messages</h3>
  <div class="table-responsive mt-4">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Contact Info</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Submitted At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($messages->num_rows > 0): $i = 1; ?>
          <?php while ($msg = $messages->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($msg['name']) ?></td>
              <td><?= htmlspecialchars($msg['contact_info']) ?></td>
              <td><?= htmlspecialchars($msg['subject']) ?></td>
              <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
              <td><?= $msg['submitted_at'] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">No messages found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
