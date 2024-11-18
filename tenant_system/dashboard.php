<?php

// database connection
include('db.php');

// Started the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);  // "s" stands for string
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password === $row['password']) {
            // Login successful, store the user info in session
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            header('Location: welcome.php'); // Redirect to a protected page
            exit();
        } else {
            header('Location: index.php?error=Invalid%20password');
            exit();
        }
    } else {
         // Username not found, redirect back with error
         header('Location: index.php?error=Username%20not%20found');
         exit();
    }
} else {
    $error_message = "";
}
?>