<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to landing page
header("Location: landing.php");
exit();
?>
