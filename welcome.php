<?php
// Include the session check file to ensure the user is logged in
include('check_login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f5f8fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header img {
            width: 50px;
            height: auto;
        }

        .header h1 {
            color: #2d3e50;
            font-size: 1.5rem;
            margin-left: 10px;
        }

        .hamburger {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .main-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .main-card h2 {
            color: #2d3e50;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            color: #fff;
            margin: 5px;
            flex: 1 1 auto;
            max-width: 150px;
        }

        .button.manager {
            background-color: #57b846;
        }

        .button.tenant {
            background-color: #5d9cec;
        }

        .sidebar {
            background-color: #fff;
            padding: 15px;
            position: fixed;
            top: 0;
            left: -250px; /* Hidden off-screen */
            height: 100%;
            width: 250px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
        }

        .sidebar.active {
            left: 0; /* Show sidebar */
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin: 15px 0;
        }

        .sidebar a {
            text-decoration: none;
            color: #2d3e50;
            font-weight: bold;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .hamburger {
                display: block; /* Show hamburger icon */
            }

            .main-card {
                max-width: 90%;
                padding: 15px;
            }

            .header h1 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 15px;
            }

            .header img {
                width: 40px;
            }

            .header h1 {
                font-size: 1rem;
            }

            .main-card {
                padding: 10px;
                max-width: 95%;
            }

            .button {
                padding: 8px 15px;
                max-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="logo.png" alt="App Logo">
        <h1>Rental Management App</h1>
        <span class="hamburger" onclick="toggleSidebar()">☰</span>
    </div>
    
    <div class="main-content">
        <div class="main-card">
            <h2>You are a property</h2>
            <div class="buttons">
                <button class="button manager" onclick="window.location.href='manager.php'">Manager</button>
                <button class="button tenant" onclick="window.location.href='tenant_form.php'">Tenant</button>
            </div>
        </div>
    </div>

    <nav class="sidebar" id="sidebar">
        <ul>
            <li><a href="tenant_form.php">Tenant Section</a></li>
            <li><a href="manager.php">Manager Section</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
