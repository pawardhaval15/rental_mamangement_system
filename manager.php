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
<html>
<head>
    <title>Manager - Rental Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Tenant Management</h1>
    <?php if (isset($uploadStatus)): ?>
        <div class="upload-status">
            <p><?php echo $successCount; ?> rows successfully inserted.</p>
            <p><?php echo $failureCount; ?> rows failed to insert.</p>
        </div>
    <?php endif; ?>
    <table border="1" id="tenantTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Property</th>
                <th>Rent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $tenants->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['tenant_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['property_name'] ?></td>
                    <td><?= $row['monthly_rent'] ?></td>
                    <td>
                    <a href="tenant_form.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    <a href="manager.php?delete_id=<?= $row['id'] ?>&confirm_delete=yes" 
                           onclick="return confirm('Are you sure you want to delete this tenant?');">
                           <button>Delete</button>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- File upload form -->
    <form action="upload_excel.php" method="POST" enctype="multipart/form-data">
    <label for="excelFile">Upload Excel/CSV File:</label>
    <input type="file" name="excelFile" id="excelFile" required>
    <button type="submit">Upload</button>
</form>

    <a href="download_excel.php" class="download-btn">Download Data as Excel</a>
</body>
</html>
