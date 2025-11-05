<?php
session_start();

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

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user with given email
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Login successful, set session
            $_SESSION['student_id'] = $id;
            $_SESSION['student_name'] = $name;

            // Redirect to dashboard
            header("Location: student_dashboard.php");
            exit();
        } else {
            // Invalid password
            echo "<script>alert('❌ Invalid email or password.'); window.history.back();</script>";
            exit;
        }
    } else {
        // User not found
        echo "<script>alert('❌ Invalid email or password.'); window.history.back();</script>";
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
