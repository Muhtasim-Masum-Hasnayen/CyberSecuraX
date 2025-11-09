<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../student_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
if ($conn->connect_error) die("Connection failed.");

$exam_id = $_GET['exam_id'] ?? null;
$student_id = $_SESSION['student_id'];
if (!$exam_id) die("Exam ID is missing");

// Fetch exam details
$exam = $conn->query("SELECT exams.*, courses.title AS course_title FROM exams JOIN courses ON exams.course_id = courses.id WHERE exams.id = $exam_id")->fetch_assoc();
if (!$exam) die("Exam not found");

// Fetch questions
$questions = $conn->query("SELECT * FROM questions WHERE exam_id = $exam_id ORDER BY id ASC");

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['answers'] ?? [];
    $score = 0;
    $total = 0;

    foreach ($answers as $question_id => $answer) {
        $question_id = (int)$question_id;
        $ans = mysqli_real_escape_string($conn, $answer);

        $q = $conn->query("SELECT * FROM questions WHERE id = $question_id AND exam_id = $exam_id")->fetch_assoc();

        $stmt = $conn->prepare("INSERT INTO student_answers (student_id, exam_id, question_id, answer) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $student_id, $exam_id, $question_id, $ans);
        $stmt->execute();
        $stmt->close();

        if ($q['question_type'] === 'MCQ' && $q['correct_answer'] === $ans) {
            $score++;
        }
        if ($q['question_type'] === 'MCQ') $total++;
    }

    $stmt = $conn->prepare("INSERT INTO exam_results (student_id, exam_id, score, total_questions) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $student_id, $exam_id, $score, $total);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('✅ Your answers have been submitted! You scored $score out of $total'); window.location.href='review_exam.php?exam_id=$exam_id';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Start Exam - CyberSecuraX</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #0a0f0d; color: #eee; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 900px; padding: 30px; }

    .question-box {
      background-color: #111; border: 1px solid #333;
      border-radius: 8px; padding: 20px; margin-bottom: 20px;
      box-shadow: 0 0 8px #1fffac30;
    }

    .question-box:hover { box-shadow: 0 0 16px #1fffac60; }
    .question-title { font-weight: bold; color: #82f5b4; }
    .option {
      background-color: #1c1c1c; border: 1px solid #444;
      padding: 8px; border-radius: 6px; margin-top: 10px;
    }

    .option input { margin-right: 10px; }
    .btn-submit {
      background-color: #82f5b4; color: #000;
      font-weight: bold; border: none;
      padding: 12px 30px; border-radius: 6px;
      margin-top: 20px;
    }

    .btn-submit:hover {
      background-color: #6cf0a8;
      box-shadow: 0 0 10px #82f5b4;
    }

    .exam-info {
      margin-bottom: 30px;
      border-bottom: 1px solid #444;
      padding-bottom: 10px;
    }

    /* Fixed Timer Box */
    .timer-box {
      position: fixed;
      top: 15px;
      right: 15px;
      background: #111;
      border: 2px solid #1fffac;
      padding: 10px 20px;
      border-radius: 10px;
      font-size: 18px;
      font-weight: bold;
      color: #82f5b4;
      z-index: 1000;
      box-shadow: 0 0 10px #1fffac60;
    }
  </style>
</head>
<body>

<div class="timer-box">
  ⏳ Time Left: <span id="timer"><?= $exam['duration'] ?>:00</span>
</div>

<div class="container">
  <div class="exam-info">
    <h3>🧠 <?= htmlspecialchars($exam['title']) ?> (<?= $exam['type'] ?>)</h3>
    <p><strong>Course:</strong> <?= htmlspecialchars($exam['course_title']) ?></p>
    <p><strong>Duration:</strong> <?= $exam['duration'] ?> minutes</p>
  </div>

  <form method="POST" id="examForm">
    <?php $i = 1; while ($q = $questions->fetch_assoc()): ?>
      <div class="question-box">
        <div class="question-title">
          <?= $i++ ?>. <?= htmlspecialchars($q['question_text']) ?>
        </div>

        <?php if ($q['question_type'] === 'MCQ'): ?>
          <div class="option"><label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> <?= $q['option_a'] ?></label></div>
          <div class="option"><label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B"> <?= $q['option_b'] ?></label></div>
          <div class="option"><label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> <?= $q['option_c'] ?></label></div>
          <div class="option"><label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> <?= $q['option_d'] ?></label></div>
        <?php else: ?>
          <textarea name="answers[<?= $q['id'] ?>]" class="form-control mt-2" rows="4" placeholder="Write your answer here..." required></textarea>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>

    <button type="submit" class="btn btn-submit w-100">Submit Answers</button>
  </form>
</div>

<script>
  let totalSeconds = <?= $exam['duration'] ?> * 60;
  const timerDisplay = document.getElementById('timer');
  const examForm = document.getElementById('examForm');

  function updateTimer() {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

    if (totalSeconds <= 0) {
      clearInterval(timerInterval);
      alert("⏰ Time is up! Submitting your exam...");
      examForm.submit();
    }
    totalSeconds--;
  }

  const timerInterval = setInterval(updateTimer, 1000);
  updateTimer(); // Initial call
</script>

</body>
</html>
