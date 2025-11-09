<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// DB connection
$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch exam results
$sql = "
  SELECT
    c.title AS course_title,
    e.title AS exam_title,
    er.score,
    er.total_questions
  FROM exam_results er
  JOIN exams e ON er.exam_id = e.id
  JOIN courses c ON e.course_id = c.id
  WHERE er.student_id = ?
  ORDER BY er.submitted_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Cyber SecuraX | Student Dashboard</title>
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
      top: 0; left: 0;
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
    }
    .sidebar nav {
      display: flex;
      flex-direction: column;
    }
    .sidebar nav a {
      padding: 12px 30px;
      color: #a3d9b0;
      font-weight: 500;
      text-decoration: none;
      font-size: 15px;
      transition: background-color 0.25s;
      display: flex;
      align-items: center;
    }
    .sidebar nav a i {
      margin-right: 15px;
      width: 20px;
      text-align: center;
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
      top: 0; left: 230px; right: 0;
      height: 60px;
      background: #0f1e17;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
      box-shadow: 0 2px 8px #1fffac30;
      z-index: 1050;
    }
    .topbar h1 {
      color: #82f5b4;
      font-size: 22px;
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
    .card-box {
      background: #111;
      border: 1px solid #333;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px #1fffac20;
      transition: all 0.3s ease;
      color: #a3d9b0;
      text-align: center;
    }
    .card-box:hover {
      box-shadow: 0 0 20px #82f5b450;
      background-color: #1e2c23;
      color: #000;
    }
    .card-box h5 {
      color: #82f5b4;
    }
    .card {
      background-color: #111;
      border: 1px solid #333;
      color: #eee;
      box-shadow: 0 0 10px #1fffac20;
    }
    .card-title {
      color: #82f5b4;
    }

    @media (max-width: 768px) {
      .sidebar { width: 60px; }
      .sidebar .logo { width: 60px; font-size: 18px; justify-content: center; }
      .sidebar nav a { padding: 12px 10px; font-size: 0; justify-content: center; }
      .sidebar nav a i { margin: 0; font-size: 20px; }
      .topbar { left: 60px; }
      .main-content { margin-left: 60px; padding: 80px 20px 40px; }
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo">
    <i class="fas fa-user-graduate"></i>&nbsp;<span class="d-none d-md-inline">CyberSecuraX</span>
  </div>
  <nav>
    <a href="student_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i><span class="d-none d-md-inline"> Dashboard</span></a>
    <a href="student_courses.php"><i class="fas fa-book-open"></i><span class="d-none d-md-inline"> View Courses</span></a>
    <a href="student_exams.php"><i class="fas fa-question-circle"></i><span class="d-none d-md-inline"> View Exams</span></a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i><span class="d-none d-md-inline"> Logout</span></a>
  </nav>
</div>

<div class="topbar">
  <h1><i class="fas fa-user-graduate"></i> Student Panel - Cyber SecuraX</h1>
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="main-content">
  <h3>Welcome, <?= htmlspecialchars($_SESSION['student_name']); ?> 👋</h3>

  <div class="row mt-4">
    <div class="col-md-6">
      <a href="student_courses.php" style="text-decoration:none;">
        <div class="card-box">
          <h5><i class="fas fa-book-open"></i> Available Courses</h5>
          <p>Explore all cybersecurity courses designed for you.</p>
        </div>
      </a>
    </div>
    <div class="col-md-6">
      <a href="student_exams.php" style="text-decoration:none;">
        <div class="card-box">
          <h5><i class="fas fa-question-circle"></i> Available Exams</h5>
          <p>Take exams to test and improve your skills.</p>
        </div>
      </a>
    </div>
  </div>

  <hr class="text-secondary my-4" />

  <h4 class="text-success mb-3">📊 Your Exam Results</h4>
  <div class="row">
    <?php if ($results->num_rows > 0): ?>
      <?php while ($row = $results->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="card p-3">
            <h5 class="card-title"><?= htmlspecialchars($row['exam_title']) ?></h5>
            <h6 style="color: #82f5b4;"><?= htmlspecialchars($row['course_title']) ?></h6>
            <p class="mb-1">✅ Obtained: <strong><?= (int)$row['score'] ?></strong></p>
            <p class="mb-0">📘 Total Marks: <strong><?= (int)$row['total_questions'] ?></strong></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No exam results found yet.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
