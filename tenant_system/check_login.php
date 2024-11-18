<?php
session_start();

// Check if the session variable 'username' is set, meaning the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page (index.php)
    header('Location: index.php');
    exit(); // Ensure no further code is executed
}
?>
