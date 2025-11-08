<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cyber SecuraX | Facilities</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: #0a0f0d;
      color: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
    }
    .header {
      background: #0f1e17;
      padding: 20px;
      text-align: center;
      box-shadow: 0 0 8px #1fffac40;
    }
    .header h1 {
      color: #82f5b4;
      margin: 0;
    }
    .section {
      padding: 80px 20px;
    }
    .section-title {
      color: #82f5b4;
      font-weight: bold;
      text-align: center;
      margin-bottom: 50px;
    }
    .facility-box {
      background: linear-gradient(135deg, #111, #0f1e17);
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 0 10px #1fffac20;
      transition: all 0.3s ease;
    }
    .facility-box:hover {
      transform: scale(1.02);
      box-shadow: 0 0 20px #82f5b470;
    }
    .facility-icon {
      font-size: 40px;
      color: #82f5b4;
      margin-bottom: 15px;
    }
    .facility-box h5 {
      color: #82f5b4;
    }
    .footer {
      background: #0f1e17;
      padding: 20px;
      text-align: center;
      color: #aaa;
    }
  </style>
</head>
<body>

<!-- Header -->
<div class="header">
  <h1><i class="fas fa-server"></i> Cyber SecuraX Facilities</h1>
  <p class="text-muted">Explore the powerful features crafted for modern cybersecurity learners</p>
</div>

<!-- Facilities Section -->
<section class="section">
  <h2 class="section-title" data-aos="fade-up">What Our Platform Offers</h2>
  <div class="container">
    <div class="row g-4">

      <div class="col-md-4" data-aos="fade-up">
        <div class="facility-box text-center">
          <div class="facility-icon"><i class="fas fa-user-shield"></i></div>
          <h5>Role-Based Access</h5>
          <p>Students, Admins, and Instructors each have secure, custom dashboards with restricted access control.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="facility-box text-center">
          <div class="facility-icon"><i class="fas fa-laptop-code"></i></div>
          <h5>Secure Exam Engine</h5>
          <p>Randomized MCQs, anti-cheat measures, countdown lockouts, and secure submission for fair assessments.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="facility-box text-center">
          <div class="facility-icon"><i class="fas fa-bug-slash"></i></div>
          <h5>Cyber Labs (Future Scope)</h5>
          <p>Practical simulations with Web Exploit, SQL Injection, and Packet Sniffing challenges (Docker-ready).</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="facility-box text-center">
          <div class="facility-icon"><i class="fas fa-certificate"></i></div>
          <h5>Auto Certification</h5>
          <p>Instantly generate personalized certificates with secure QR verification after passing final exams.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
        <div class="facility-box text-center">
          <div class="facility-icon"><i class="fas fa-chart-line"></i></div>
          <h5>Performance Analytics</h5>
          <p>Visual progress reports, success rates, topic heatmaps, and performance tracking with AI suggestions.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
        <div class="facility-box text-center">
          <div class="facility-icon"><i class="fas fa-gamepad"></i></div>
          <h5>Gamification & Ranking</h5>
          <p>Earn XP, rank on leaderboards, and unlock badges like “Phishing Slayer” & “Firewall Master”.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  &copy; <?= date('Y'); ?> Cyber SecuraX • All rights reserved • Designed by Lazy Boyz
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init();</script>

</body>
</html>
