<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) die("Connection failed.");

// ✅ Fetch courses with exam count
$sql = "
  SELECT
    c.id, c.title, c.description,
    COUNT(e.id) AS exam_count
  FROM courses c
  LEFT JOIN exams e ON c.id = e.course_id
  GROUP BY c.id
  ORDER BY c.created_at DESC
";
$courses = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Cyber SecuraX | Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
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
    }

    .card-box:hover {
      box-shadow: 0 0 24px #1fffac70;
      background: #13221d;
    }

    .card-box h5 {
      color: #82f5b4;
      font-weight: bold;
    }

    .card-box p {
      font-size: 14px;
      color: #bfbfbf;
    }

    .course-card {
      background: #111;
      border: 1px solid #333;
      border-radius: 10px;
      padding: 20px;
      color: #ddd;
      transition: 0.3s;
    }

    .course-card:hover {
      background: #1c2a23;
      box-shadow: 0 0 20px #1fffac50;
    }

    .course-card h5 {
      color: #82f5b4;
    }

    .course-card p {
      font-size: 14px;
      color: #bbb;
    }

    .exam-count {
      font-size: 13px;
      color: #aaa;
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

  <div class="sidebar">
    <div class="logo">
      <i class="fas fa-shield-alt"></i>&nbsp;<span class="d-none d-md-inline">CyberSecuraX</span>
    </div>
    <nav>
      <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span class="d-none d-md-inline">Dashboard</span></a>
      <a href="admin_users.php"><i class="fas fa-users"></i> <span class="d-none d-md-inline">Manage Users</span></a>
      <a href="create_course.php"><i class="fas fa-book-open"></i> <span class="d-none d-md-inline">Create Course</span></a>
      <a href="create_exam.php"><i class="fas fa-question-circle"></i> <span class="d-none d-md-inline">Create Exam</span></a>
      <a href="add_question.php"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add Question</span></a>
      <a href="admin_messages.php"><i class="fas fa-message"></i> <span class="d-none d-md-inline">Messages</span></a>

      <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span></a>
    </nav>
  </div>

  <div class="topbar">
    <h1><i class="fas fa-shield-alt"></i> Admin Panel - Cyber SecuraX</h1>
    <a href="../logout.php" class="logout-btn">Logout</a>
  </div>

  <div class="main-content">
    <h3>Welcome, <?= htmlspecialchars($_SESSION['admin_name']); ?> 👋</h3>

    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card-box">
          <h5><i class="fas fa-book-open"></i> Create Courses</h5>
          <p>Add new cybersecurity courses and define structured learning paths.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-box">
          <h5><i class="fas fa-question-circle"></i> Manage Exams</h5>
          <p>Create and manage MCQs and written questions by course and module.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-box">
          <h5><i class="fas fa-users-cog"></i> Monitor Students</h5>
          <p>View student performance, submissions, and progress tracking reports.</p>
        </div>
      </div>
    </div>

    <hr class="text-muted my-4">

    <h4 class="text-success mb-3"><i class="fas fa-layer-group"></i> All Courses Overview</h4>
    <div class="row">
      <?php while ($row = $courses->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="course-card h-100">
            <h5><?= htmlspecialchars($row['title']) ?></h5>
            <p><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
            <p class="exam-count">📘 Total Exams: <strong><?= $row['exam_count'] ?></strong></p>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

</body>
</html>
