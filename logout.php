<?php
session_start(); // Start session

// Destroy the session to log the user out
session_unset();    // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page after logout
header("Location: login.php");
exit;
?>
