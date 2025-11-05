<?php
// DB credentials
$host = "localhost";
$dbname = "CyberSecuraX";
$username = "root";
$password = ""; // default XAMPP password is blank

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];

    // 1. Check if passwords match
    if ($password !== $confirm) {
        echo "<script>alert('❌ Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // 2. Check if admin already exists
    $check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('⚠️ This email is already registered as admin.'); window.history.back();</script>";
        exit;
    }

    // 3. Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 4. Insert into admin table
    $stmt = $conn->prepare("INSERT INTO admin (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Admin registration successful! Please log in.'); window.location.href='admin_login.php';</script>";
    } else {
        echo "<script>alert('❌ Something went wrong. Try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $check->close();
}

$conn->close();
?>
