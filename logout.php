<?php
// Start session
session_start();

// Destroy all session data
session_destroy();

// Redirect to login page with a logout confirmation message
header("Location: index.php?message=Successfully logged out.");
exit();
?>
