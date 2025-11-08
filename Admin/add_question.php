<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: ../admin_login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) die("DB connection failed");

$exam_id = $_GET['exam_id'] ?? null;

// Fetch all exams
$exams_result = $conn->query("SELECT exams.id, exams.title AS exam_title, exams.type, courses.title AS course_title FROM exams JOIN courses ON exams.course_id = courses.id");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_question'])) {
  $question = $_POST['question_text'];
  $type = $_POST['exam_type'];
  $file_path = null;

  if (isset($_FILES['question_file']) && $_FILES['question_file']['error'] === 0) {
    $uploadDir = "uploads/questions/";
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = basename($_FILES['question_file']['name']);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $newName = uniqid("qfile_") . "." . $ext;
    $destination = $uploadDir . $newName;

    if (move_uploaded_file($_FILES['question_file']['tmp_name'], $destination)) {
      $file_path = $destination;
    }
  }

  if ($type === "MCQ") {
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_answer'];

    $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_answer, question_type, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, 'MCQ', ?)");
    $stmt->bind_param("isssssss", $exam_id, $question, $a, $b, $c, $d, $correct, $file_path);
  } else {
    $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_text, question_type, file_path) VALUES (?, ?, 'Written', ?)");
    $stmt->bind_param("iss", $exam_id, $question, $file_path);
  }

  $stmt->execute();
  $stmt->close();
  header("Location: add_question.php?exam_id=$exam_id");
  exit();
}

if (isset($_GET['delete_q'])) {
  $qid = (int)$_GET['delete_q'];
  $conn->query("DELETE FROM questions WHERE id=$qid AND exam_id=$exam_id");
  header("Location: add_question.php?exam_id=$exam_id");
  exit();
}

$selected_exam = null;
$questions_result = null;
if ($exam_id) {
  $selected_exam = $conn->query("SELECT exams.*, courses.title AS course_title FROM exams JOIN courses ON exams.course_id = courses.id WHERE exams.id = $exam_id")->fetch_assoc();
  $questions_result = $conn->query("SELECT * FROM questions WHERE exam_id = $exam_id ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Questions</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .exam-card {
      background: #111;
      border: 1px solid #333;
      padding: 15px;
      border-radius: 8px;
      color: #82f5b4;
      margin-bottom: 20px;
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
    .question-box {
      background-color: #1c1c1c;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 6px;
    }
    .question-box small {
      color: #aaa;
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

<div class="sidebar">
    <div class="logo">
      <i class="fas fa-shield-alt"></i>&nbsp;<span class="d-none d-md-inline">CyberSecuraX</span>
    </div>
    <nav>
      <a href="admin_dashboard.php" ><i class="fas fa-tachometer-alt"></i> <span class="d-none d-md-inline">Dashboard</span></a>
      <a href="admin_users.php"><i class="fas fa-users"></i> <span class="d-none d-md-inline">Manage Users</span></a>
      <a href="create_course.php"><i class="fas fa-book-open"></i> <span class="d-none d-md-inline">Create Course</span></a>
      <a href="create_exam.php"><i class="fas fa-question-circle"></i> <span class="d-none d-md-inline">Create Exam</span></a>
      <a href="add_question.php"class="active"><i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Add Question</span></a>
      <a href="admin_messages.php"><i class="fas fa-message"></i> <span class="d-none d-md-inline">Messages</span></a>

      <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span></a>
    </nav>
  </div>

<div class="topbar">
  <h1><i class="fas fa-shield-alt"></i> Admin Panel - Cyber SecuraX</h1>
  <a href="../logout.php" class="logout-btn">Logout</a>
</div>

<div class="main-content">
  <h2>📝 Manage Exam Questions</h2>

  <?php if (!$exam_id): ?>
    <h4 class="mt-4">Select an Exam:</h4>
    <div class="row">
      <?php while($exam = $exams_result->fetch_assoc()): ?>
        <div class="col-md-4">
          <a href="?exam_id=<?= $exam['id'] ?>" class="text-decoration-none">
            <div class="exam-card">
              <h5><?= htmlspecialchars($exam['exam_title']) ?> (<?= $exam['type'] ?>)</h5>
              <p><small><?= htmlspecialchars($exam['course_title']) ?></small></p>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <h4 class="text-success">Add Questions to: <?= htmlspecialchars($selected_exam['title']) ?> (<?= $selected_exam['type'] ?>)</h4>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
      <input type="hidden" name="exam_type" value="<?= $selected_exam['type'] ?>">
      <div class="mb-3">
        <label>Question Text</label>
        <textarea name="question_text" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label>Attach File (optional - image/pdf/docx)</label>
        <input type="file" name="question_file" class="form-control">
      </div>

      <?php if ($selected_exam['type'] === "MCQ"): ?>
        <div class="row">
          <div class="col-md-6 mb-2"><label>Option A</label><input type="text" name="option_a" class="form-control" required></div>
          <div class="col-md-6 mb-2"><label>Option B</label><input type="text" name="option_b" class="form-control" required></div>
          <div class="col-md-6 mb-2"><label>Option C</label><input type="text" name="option_c" class="form-control" required></div>
          <div class="col-md-6 mb-2"><label>Option D</label><input type="text" name="option_d" class="form-control" required></div>
        </div>
        <div class="mb-3">
          <label>Correct Answer</label>
          <select name="correct_answer" class="form-control" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
        </div>
      <?php endif; ?>

      <button type="submit" name="add_question" class="btn w-100">➕ Add Question</button>
    </form>

    <h5>📚 Previously Added Questions:</h5>
    <?php while ($q = $questions_result->fetch_assoc()): ?>
      <div class="question-box">
        <strong>Q<?= $q['id'] ?>:</strong> <?= nl2br(htmlspecialchars($q['question_text'])) ?><br/>
        <?php if ($q['file_path']): ?>
          <p>📎 <a href="<?= $q['file_path'] ?>" target="_blank" style="color:#82f5b4;">Attached File</a></p>
        <?php endif; ?>
        <?php if ($q['question_type'] === 'MCQ'): ?>
          <small>A: <?= $q['option_a'] ?> | B: <?= $q['option_b'] ?> | C: <?= $q['option_c'] ?> | D: <?= $q['option_d'] ?> </small><br>
          <strong class="text-warning">✔ Correct: <?= $q['correct_answer'] ?></strong><br>
        <?php endif; ?>
        <a href="?delete_q=<?= $q['id'] ?>&exam_id=<?= $exam_id ?>" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Delete this question?')">🗑️ Delete</a>
      </div>
    <?php endwhile; ?>
    <a href="add_question.php" class="btn btn-secondary mt-4">← Back to Exam List</a>
  <?php endif; ?>
</div>

</body>
</html>
