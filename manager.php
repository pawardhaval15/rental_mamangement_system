<?php

include('db.php');

// Check if delete_id is set via GET (clicked the delete button)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Confirm the deletion (this could be done in a single request or by asking via a separate confirmation form)
    if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 'yes') {
        // Perform the delete query
        $stmt = $conn->prepare("DELETE FROM tenants WHERE id = ?");
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            // Redirect to the same page after successful deletion
            header("Location: manager.php");  // Redirect to refresh the page
            exit;
        } else {
            echo "<p style='color: red;'>Error deleting tenant.</p>";
        }
        $stmt->close();
    }
}

$tenants = $conn->query("SELECT * FROM tenants");
// Get the upload status from the session, if available
if (isset($_SESSION['upload_status'])) {
    $uploadStatus = $_SESSION['upload_status'];
    $successCount = $uploadStatus['success'];
    $failureCount = $uploadStatus['failure'];

    // Clear the session after displaying the message
    unset($_SESSION['upload_status']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Rental Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            position: fixed;
            left: -250px;
            width: 250px;
            top: 0;
            transition: all 0.3s;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .content.shifted {
            margin-left: 250px;
        }

        .hamburger {
            font-size: 30px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .content.shifted {
                margin-left: 0;
            }

            .sidebar {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-dark" id="sidebar">
            <h4 class="text-white p-3">Menu</h4>
            <a href="tenant_form.php">Tenant Section</a>
            <a href="manager.php">Manager Section</a>
            <a href="logout.php">Logout</a>
        </nav>

        <!-- Content -->
        <div class="content w-100" id="main-content">
            <header class="d-flex justify-content-between align-items-center p-3 bg-light shadow-sm">
                <span class="hamburger" id="sidebar-toggle">☰</span>
                <h1 class="m-0">Tenant Management</h1>
            </header>

            <div class="container mt-4">
                <a href="welcome.php" class="btn btn-primary mb-3">Back</a>

                <!-- Upload Status -->
                <?php if (isset($uploadStatus)): ?>
                    <div class="alert alert-info">
                        <p><?php echo $successCount; ?> rows successfully inserted.</p>
                        <p><?php echo $failureCount; ?> rows failed to insert.</p>
                    </div>
                <?php endif; ?>

                <!-- Tenant Table -->
                <h2>Tenants</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Property</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Rent</th>
                                <th>Rent Pay Date</th>
                                <th>Nex-Year Rent Increase<th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $tenants->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['site_name'] ?></td>
                                    <td><?= $row['tenant_name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['monthly_rent'] ?></td>
                                    <td><?= $row['rent_start_date'] ?></td>
                                    <td><?= $row['increased_rent'] ?></td>
                                    <td>
                                        <a href="tenant_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="manager.php?delete_id=<?= $row['id'] ?>&confirm_delete=yes"
                                           onclick="return confirm('Are you sure you want to delete this tenant?');"
                                           class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- File Upload Section -->
                <form action="upload_excel.php" method="POST" enctype="multipart/form-data" class="mt-4">
                    <label for="excelFile" class="form-label">Upload Excel/CSV File:</label>
                    <div class="input-group">
                        <input type="file" name="excelFile" id="excelFile" class="form-control" required>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
                </form>

                <!-- Download Button -->
                <a href="download_excel.php" class="btn btn-secondary mt-3">Download Data as Excel</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('shifted');
        });
    </script>
</body>
</html>

