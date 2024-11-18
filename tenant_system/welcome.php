<?php
// Include the session check file to ensure the user is logged in
include('check_login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="content">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Use the menu to navigate.</p>
    </main>
    <nav class="sidebar">
        <ul>
            <li><a href="tenant_form.php">Tenant Section</a></li>
            <li><a href="manager.php">Manager Section</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

</body>
</html>





