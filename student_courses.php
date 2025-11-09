<?php
session_start();
if (!isset($_SESSION['student_id'])) {
  header("Location: student_login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "CyberSecuraX");
$student_id = $_SESSION['student_id'];

$sql = "
SELECT
  c.id,
  c.title,
  c.description,
  IFNULL(total_exams.total, 0) AS total_exams,
  IFNULL(attempted_exams.done, 0) AS attempted_exams,
  CASE
    WHEN total_exams.total = 0 THEN 0
    ELSE ROUND((attempted_exams.done / total_exams.total) * 100)
  END AS progress
FROM courses c
LEFT JOIN (
  SELECT course_id, COUNT(*) AS total
  FROM exams
  GROUP BY course_id
) total_exams ON total_exams.course_id = c.id

LEFT JOIN (
  SELECT e.course_id, COUNT(DISTINCT sa.exam_id) AS done
  FROM student_answers sa
  JOIN exams e ON sa.exam_id = e.id
  WHERE sa.student_id = ?
  GROUP BY e.course_id
) attempted_exams ON attempted_exams.course_id = c.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$courses = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Courses - Cyber SecuraX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      background: #0a0f0d;
      color: #eee;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      width: 230px;
      background: #111;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px 0;
      border-right: 1px solid #222;
      overflow-y: auto;
    }
    .sidebar .logo {
      color: #82f5b4;
      text-align: center;
      font-size: 20px;
      margin-bottom: 30px;
    }
    .sidebar nav a {
      display: block;
      padding: 12px 25px;
      color: #ccc;
      text-decoration: none;
      font-size: 15px;
      transition: background 0.2s;
    }
    .sidebar nav a:hover,
    .sidebar nav a.active {
      background: #1a1a1a;
      color: #82f5b4;
    }
    .topbar {
      margin-left: 230px;
      background: #0f1e17;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px #1fffac30;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .topbar h1 {
      font-size: 22px;
      color: #82f5b4;
      margin: 0;
    }
    .logout-btn {
      background-color: #82f5b4;
      color: #000;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
    }
    .logout-btn:hover {
      background-color: #6cf0a8;
      box-shadow: 0 0 10px #82f5b4;
    }
    .main-content {
      margin-left: 230px;
      padding: 40px 30px;
    }
    .search-bar {
      margin-bottom: 25px;
      max-width: 400px;
    }
    .card-box {
      background: #111;
      border: 1px solid #333;
      border-radius: 10px;
      padding: 20px 25px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px #1fffac20;
      transition: all 0.3s ease;
      cursor: pointer;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .card-box:hover {
      box-shadow: 0 0 20px #82f5b450;
      background: #18261a;
    }
    .card-box h5 {
      color: #82f5b4;
      margin-bottom: 15px;
    }
    .card-box p {
      font-size: 14px;
      color: #bbb;
      flex-grow: 1;
    }
    .progress {
      height: 16px;
      background-color: #222;
      border-radius: 10px;
      overflow: hidden;
      margin-top: 15px;
    }
    .progress-bar {
      background-color: #82f5b4;
      font-weight: 600;
      font-size: 12px;
      line-height: 16px;
      color: #000;
    }
    small {
      font-size: 12px;
      color: #aaa;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <i class="fas fa-user-graduate"></i>&nbsp;<span class="d-none d-md-inline">CyberSecuraX</span>
    </div>
    <nav>
      <a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span class="d-none d-md-inline">Dashboard</span></a>
      <a href="student_courses.php" class="active"><i class="fas fa-book-open"></i> <span class="d-none d-md-inline">View Courses</span></a>
      <a href="student_exams.php"><i class="fas fa-question-circle"></i> <span class="d-none d-md-inline">View Exams</span></a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span></a>
    </nav>
  </div>

  <!-- Topbar -->
  <div class="topbar">
    <h1><i class="fas fa-user-graduate"></i> Student Panel - Cyber SecuraX</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h3 class="text-success mb-4">📚 Available Courses</h3>

    <!-- Search -->
    <input
      type="text"
      id="courseSearch"
      class="form-control search-bar"
      placeholder="Search courses..."
      onkeyup="filterCourses()"
      aria-label="Search courses"
    />

    <div class="row" id="courseContainer">
      <?php if ($courses->num_rows > 0): ?>
        <?php while ($row = $courses->fetch_assoc()): ?>
          <div class="col-md-6 col-lg-4 course-card" data-title="<?= htmlspecialchars(strtolower($row['title'])) ?>">
            <div class="card-box" onclick="showCourseDetails('<?= htmlspecialchars(addslashes($row['title'])) ?>', '<?= htmlspecialchars(addslashes($row['description'])) ?>', <?= (int)$row['progress'] ?>)">
              <h5><i class="fas fa-book"></i> <?= htmlspecialchars($row['title']) ?></h5>
              <p><?= htmlspecialchars(strlen($row['description']) > 120 ? substr($row['description'],0,117).'...' : $row['description']) ?></p>

              <?php if ((int)$row['total_exams'] === 0): ?>
                <small>🛑 No Exam Assigned (Yet)</small>
              <?php endif; ?>

              <div class="progress">
                <div
                  class="progress-bar"
                  role="progressbar"
                  style="width: <?= (int)$row['progress'] ?>%;"
                  aria-valuenow="<?= (int)$row['progress'] ?>"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ><?= (int)$row['progress'] ?>%</div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No courses available at the moment.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Course Detail Modal -->
  <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-dark text-light">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="courseModalLabel"></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="courseModalDescription"></div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function filterCourses() {
      const input = document.getElementById('courseSearch').value.toLowerCase();
      const cards = document.querySelectorAll('.course-card');
      cards.forEach(card => {
        const title = card.getAttribute('data-title');
        card.style.display = title.includes(input) ? '' : 'none';
      });
    }

    const courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
    function showCourseDetails(title, description, progress) {
      document.getElementById('courseModalLabel').innerText = title;
      document.getElementById('courseModalDescription').innerText = description + `\n\nProgress: ${progress}%`;
      courseModal.show();
    }
  </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
