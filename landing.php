<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cyber SecuraX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap & Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background-color: #0a0f0d;
      color: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
    }
    .topbar {
      background-color: #0f1e17;
      padding: 5px 20px;
      color: #82f5b4;
      font-size: 14px;
    }
    .navbar {
      background-color: #111;
    }
    .navbar-nav .nav-link {
      color: #82f5b4 !important;
    }
    .navbar-nav .nav-link:hover {
      color: #ffffff !important;
    }
    .hero {
      background: linear-gradient(135deg, #0a0f0d 60%, #134033);
      padding: 100px 30px;
      text-align: center;
    }
    .hero h1 {
      font-size: 3rem;
      color: #82f5b4;
      text-shadow: 0 0 10px #2fffcc;
    }
    .hero p {
      font-size: 1.2rem;
      color: #bbb;
    }
    .section {
      padding: 80px 30px;
    }
    .section-title {
      color: #82f5b4;
      text-align: center;
      margin-bottom: 40px;
    }
    .feature-icon {
      font-size: 40px;
      color: #2fffcc;
      margin-bottom: 15px;
    }
    .footer {
      background-color: #0f1e17;
      padding: 20px;
      text-align: center;
      color: #aaa;
    }
    .glitch-text {
      font-weight: bold;
      position: relative;
      display: inline-block;
      color: #82f5b4;
      overflow: hidden;
      line-height: 1;
    }

    .glitch-text::after {
      content: attr(data-text);
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      color: #82f5b4;
      text-shadow: 2px 0 #ff005e, -2px 0 #82f5b4;
      animation: glitch 1s infinite;
      pointer-events: none;
    }


    @keyframes glitch {
      0%   { clip-path: inset(0 0 5% 0); text-shadow: 2px 0 #ff005e, -2px 0 #ff005e; } /* Pink start */
      15%  { clip-path: inset(10% 0 85% 0); text-shadow: -2px -1px #ff005e, 2px 2px #ff005e; }
      30%  { clip-path: inset(20% 0 70% 0); text-shadow: 1px 2px #ff005e, -2px 0 #ff005e; }
      45%  { clip-path: inset(30% 0 60% 0); text-shadow: -1px -1px #ff005e, 1px 2px #ff005e; }
      60%  { clip-path: inset(40% 0 50% 0); text-shadow: 2px 1px #ff005e, -2px 0 #ff005e; }
      75%  { clip-path: inset(30% 0 60% 0); text-shadow: -1px -1px #82f5b4, 1px 2px #ff005e; } /* transition */
      90%  { clip-path: inset(20% 0 70% 0); text-shadow: 2px 1px #82f5b4, -2px 0 #ff005e; }
      100% { clip-path: inset(0 0 5% 0); text-shadow: 2px 0 #82f5b4, -2px 0 #82f5b4; } /* Green finish */
    }


  </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar text-end">
  Empowering Future Defenders • Learn • Practice • Certify
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
  <div class="container">
    <a class="navbar-brand glitch-text" href="#" data-text=" ">Cyber SecuraX</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="facilities.php">Facilities</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div data-aos="fade-up">
    <h1>Welcome to <span class="glitch-text" data-text="Cyber SecuraX">Cyber SecuraX</span></h1>
    <p>Train. Test. Defend. The ultimate Cyber Security Exam Platform for future warriors.</p>
    <a href="register.php" class="btn btn-success mt-4 px-4 py-2">Get Started</a>
  </div>
</section>

<!-- Student Facilities -->
<section class="section" id="features">
  <h2 class="section-title" data-aos="fade-up">Student Facilities</h2>
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4 text-center" data-aos="fade-right">
        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
        <h5>Secure Exam Interface</h5>
        <p>Randomized questions, anti-cheat logic & timer-lockdown ensure fair assessment.</p>
      </div>
      <div class="col-md-4 text-center" data-aos="fade-up">
        <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
        <h5>MCQ & Written Practice</h5>
        <p>Interactive exams for each topic, plus real-world written challenge questions.</p>
      </div>
      <div class="col-md-4 text-center" data-aos="fade-left">
        <div class="feature-icon"><i class="fas fa-certificate"></i></div>
        <h5>Instant Certification</h5>
        <p>Earn digital certificates with verification QR after passing official exams.</p>
      </div>
    </div>
  </div>
</section>

<!-- About -->
<section class="section bg-dark" id="about">
  <div class="container">
    <h2 class="section-title" data-aos="zoom-in">About Cyber SecuraX</h2>
    <p class="text-center" data-aos="fade-up" style="max-width: 800px; margin: auto;">
      <strong>Cyber SecuraX</strong> is a full-stack cybersecurity learning & exam platform designed to prepare the next generation of cyber defenders. With AI-enhanced question banks, secure testing environments, and a student-first UI/UX, our goal is to make cybersecurity education accessible, rigorous, and industry-ready.
    </p>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  &copy; <?= date('Y'); ?> Cyber SecuraX • Developed by Muhtasim Masum Hasnayen
</footer>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
