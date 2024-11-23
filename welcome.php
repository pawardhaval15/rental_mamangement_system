<?php
// Include the session check file to ensure the user is logged in
include('check_login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #495057;
            padding-left: 10px;
            transition: padding-left 0.2s ease-in-out;
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.collapsed {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <ul>
                <li><a href="manager.php">Manager Section</a></li>
                <li><a href="tenant_form.php">Tenant Section</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content collapsed" id="main-content">
            <div class="d-flex justify-content-between align-items-center">
                <span class="hamburger" onclick="toggleSidebar()">☰</span>
                <h1 class="text-center w-100">Welcome to Rental Management</h1>
            </div>
            <div class="container mt-5">
                <div class="row text-center">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Manager</h5>
                                <p class="card-text">Access the Manager's section to manage properties and tenants.</p>
                                <a href="manager.php" class="btn btn-primary">Go to Manager</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Tenant</h5>
                                <p class="card-text">Access the Tenant's section to manage your account and rental details.</p>
                                <a href="tenant_form.php" class="btn btn-info">Go to Tenant</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('collapsed');
        }
    </script>
</body>
</html>
