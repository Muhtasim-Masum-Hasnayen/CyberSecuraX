<?php
// DB connection settings
$host = "localhost";
$dbname = "CyberSecuraX";
$username = "root";
$password = ""; // Default for XAMPP

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get and sanitize inputs
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // 1. Check if passwords match
    if ($password !== $confirm) {
        echo "<script>alert('❌ Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // 2. Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('⚠️ Email is already registered. Try logging in.'); window.history.back();</script>";
        exit;
    }

    // 3. Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 4. Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration successful! Please log in.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('❌ Something went wrong. Please try again later.'); window.history.back();</script>";
    }

    $stmt->close();
    $check->close();
}

$conn->close();
?>
