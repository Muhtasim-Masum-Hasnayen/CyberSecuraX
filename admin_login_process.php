<?php
session_start();

// DB credentials
$host = "localhost";
$dbname = "CyberSecuraX";
$username = "root";
$password = ""; // Default for XAMPP

// Connect to DB
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $pass  = $_POST["password"];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, name, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If email exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($adminId, $adminName, $hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($pass, $hashedPassword)) {
            $_SESSION['admin_id'] = $adminId;
            $_SESSION['admin_name'] = $adminName;

            // Redirect to dashboard
            echo "<script>alert('✅ Login successful!'); window.location.href='Admin/admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('❌ Incorrect password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('❌ Admin not found with this email.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
