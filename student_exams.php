<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../student_login.php");
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch exams and their course titles
$sql = "SELECT exams.id, exams.title AS exam_title, exams.type, exams.duration, courses.title AS course_title
        FROM exams
        JOIN courses ON exams.course_id = courses.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Exams - Cyber SecuraX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: #0a0f0d;
      color: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
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
    }
    .sidebar .logo {
      position: fixed;
      top: 0;
      height: 60px;
      width: 230px;
      background: #082a1c;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #82f5b4;
      font-weight: bold;
      font-size: 20px;
    }
    .sidebar nav a {
      display: flex;
      align-items: center;
      padding: 12px 30px;
      color: #a3d9b0;
      text-decoration: none;
    }
    .sidebar nav a i {
      margin-right: 10px;
    }
    .sidebar nav a:hover,
    .sidebar nav a.active {
      background-color: #1fffac;
      color: #000;
      font-weight: bold;
      border-radius: 6px;
    }
    .topbar {
      position: fixed;
      top: 0;
      left: 230px;
      right: 0;
      height: 60px;
      background: #0f1e17;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
      box-shadow: 0 2px 8px #1fffac30;
    }
    .main-content {
      margin-left: 230px;
      padding: 80px 30px 40px;
    }
    .card-box {
      background: #111;
      border: 1px solid #333;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      color: #a3d9b0;
      box-shadow: 0 0 10px #1fffac20;
      transition: 0.3s;
    }
    .card-box:hover {
      background-color: #1c2c25;
      box-shadow: 0 0 20px #1fffac50;
    }
    .btn-start {
      background-color: #82f5b4;
      color: #000;
      font-weight: bold;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      margin-top: 10px;
    }
    .btn-start:hover {
      background-color: #6cf0a8;
      box-shadow: 0 0 8px #82f5b4;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo">
    <i class="fas fa-user-graduate"></i>&nbsp; CyberSecuraX
  </div>
  <nav>
    <a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="student_courses.php"><i class="fas fa-book-open"></i> Courses</a>
    <a href="student_exams.php" class="active"><i class="fas fa-question-circle"></i> Exams</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </nav>
</div>

<div class="topbar">
  <h4><i class="fas fa-question-circle"></i> Available Exams</h4>
  <a href="../logout.php" class="btn btn-sm btn-light">Logout</a>
</div>

<div class="main-content">
  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card-box">
            <h5><?= htmlspecialchars($row['exam_title']) ?></h5>
            <p><strong>Course:</strong> <?= htmlspecialchars($row['course_title']) ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars($row['type']) ?></p>
            <p><strong>Duration:</strong> <?= $row['duration'] ?> mins</p>
            <a href="start_exam.php?exam_id=<?= $row['id'] ?>" class="btn btn-start">Start Exam</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No exams available at the moment.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
