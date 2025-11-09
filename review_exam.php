<?php
session_start();
if (!isset($_SESSION['student_id'])) {
  header("Location: ../student_login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");

$exam_id = $_GET['exam_id'] ?? null;
$student_id = $_SESSION['student_id'];

if (!$exam_id) die("Exam ID missing.");

// Fetch exam and student info
$exam = $conn->query("SELECT title FROM exams WHERE id = $exam_id")->fetch_assoc();
$student = $conn->query("SELECT name FROM users WHERE id = $student_id")->fetch_assoc();

// Fetch submitted answers
$answers = $conn->query("SELECT sa.*, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_answer, q.question_type
                         FROM student_answers sa
                         JOIN questions q ON sa.question_id = q.id
                         WHERE sa.exam_id = $exam_id AND sa.student_id = $student_id");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Review Exam - <?= htmlspecialchars($exam['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #0a0f0d;
      color: #eee;
      padding: 20px;
      font-family: 'Segoe UI', sans-serif;
    }

    .question-box {
      background: #111;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      border: 1px solid #333;
    }

    .correct { color: #82f5b4; font-weight: bold; }
    .wrong { color: #ff6b6b; font-weight: bold; }

    .logo {
      height: 60px;
    }

    @media print {
      body {
        background: white;
        color: black;
        font-family: Arial, sans-serif;
      }

      .no-print {
        display: none !important;
      }

      .question-box {
        border: 1px solid #888;
        background: white;
        color: black;
      }

      .logo {
        height: 60px;
      }
    }
  </style>
</head>
<body>

  <div class="no-print" style="text-align: right;">
    <button onclick="window.print()" class="btn btn-success mb-3">📄 Print / Save as PDF</button>
  </div>

  <div class="text-center mb-4">
    <img src="https://i.ibb.co/6rXfMt6/logo-secure.png" class="logo" alt="Cyber SecuraX Logo">
    <h2 class="text-success mt-2">Cyber SecuraX - Exam Review</h2>
    <p><strong>Student:</strong> <?= htmlspecialchars($student['name']) ?> |
       <strong>Exam:</strong> <?= htmlspecialchars($exam['title']) ?></p>
  </div>

  <?php $i = 1; while ($row = $answers->fetch_assoc()): ?>
    <div class="question-box">
      <strong>Q<?= $i++ ?>. <?= htmlspecialchars($row['question_text']) ?></strong>
      <?php if ($row['question_type'] === 'MCQ'): ?>
        <ul>
          <li>A. <?= $row['option_a'] ?></li>
          <li>B. <?= $row['option_b'] ?></li>
          <li>C. <?= $row['option_c'] ?></li>
          <li>D. <?= $row['option_d'] ?></li>
        </ul>
        <p>Your Answer:
          <span class="<?= ($row['answer'] === $row['correct_answer']) ? 'correct' : 'wrong' ?>">
            <?= $row['answer'] ?> <?= ($row['answer'] === $row['correct_answer']) ? '(Correct)' : '(Wrong)' ?>
          </span>
        </p>
        <p>Correct Answer: <span class="correct"><?= $row['correct_answer'] ?></span></p>
      <?php else: ?>
        <p>Your Written Answer:</p>
        <div style="background:#222;padding:10px;border-radius:6px"><?= nl2br(htmlspecialchars($row['answer'])) ?></div>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>

</body>
</html>
