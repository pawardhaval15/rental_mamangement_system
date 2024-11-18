<?php
include 'db.php';
$message = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch tenant data for editing
    $result = $conn->query("SELECT * FROM tenants WHERE id = '$id'");

    if ($result->num_rows > 0) {
        $tenant = $result->fetch_assoc();
    } else {
        $message = "<p style='color: red;'>No tenant found with this ID.</p>";
    }
} else {
    // Initialize the tenant data variables for the form
    $tenant_name = $address = $mobile_no = $email = $property_name = $property_type = $property_location = $property_owners = $monthly_rent = $deposit = $rent_status = '';
}

// Handle form submission for insert/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $tenant_name = $_POST['tenant_name'];
    $address = $_POST['address'];
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];
    $property_name = $_POST['property_name'];
    $property_type = $_POST['property_type'];
    $property_location = $_POST['property_location'];
    $property_owners = $_POST['property_owners'];
    $monthly_rent = $_POST['monthly_rent'];
    $deposit = $_POST['deposit'];
    $rent_status = $_POST['rent_status'];
    $amount_paid = $_POST['amount_paid'];
    $amount_pending = $_POST['amount_pending'];
    $payment_mode = $_POST['payment_mode'];
    $transaction_details = $_POST['transaction_details'];
    $bank_name = $_POST['bank_name'];

    if (isset($_POST['id'])) {
        // Update the tenant data
        $id = $_POST['id'];
    
        $stmt = $conn->prepare(
            "UPDATE tenants
            SET tenant_name = ?,
                address = ?,
                mobile_no = ?,
                email = ?,
                property_name = ?,
                property_type = ?,
                property_location = ?,
                property_owners = ?,
                monthly_rent = ?,
                deposit = ?,
                rent_status = ?
            WHERE id = ?"
        );

        // Corrected type string to include 12 variables
        $stmt->bind_param(
            "ssssssssddsi",
            $tenant_name,
            $address,
            $mobile_no,
            $email,
            $property_name,
            $property_type,
            $property_location,
            $property_owners,
            $monthly_rent,
            $deposit,
            $rent_status,
            $id
        );

        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Tenant details updated successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        // Insert new tenant data
        $stmt = $conn->prepare("INSERT INTO tenants (tenant_name, address, mobile_no, email, property_name, property_type, property_location, property_owners, monthly_rent, deposit, rent_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssdds", $tenant_name, $address, $mobile_no, $email, $property_name, $property_type, $property_location, $property_owners, $monthly_rent, $deposit, $rent_status);

        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Tenant details inserted successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Form</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST">
        <!-- Hidden field to store tenant ID (for editing) -->
        <?php if (isset($tenant)): ?>
            <input type="hidden" name="id" value="<?= $tenant['id'] ?>">
        <?php endif; ?>

        <label for="tenant_name">Tenant Name:</label>
        <input type="text" name="tenant_name" id="tenant_name" value="<?= isset($tenant) ? $tenant['tenant_name'] : '' ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?= isset($tenant) ? $tenant['address'] : '' ?>" required>

        <label for="mobile_no">Contact:</label>
        <input type="text" name="mobile_no" id="mobile_no" value="<?= isset($tenant) ? $tenant['mobile_no'] : '' ?>" required>

        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?= isset($tenant) ? $tenant['email'] : '' ?>" required>

        <label for="property_name">Property:</label>
        <input type="text" name="property_name" id="property_name" value="<?= isset($tenant) ? $tenant['property_name'] : '' ?>" required>

        <label for="property_type">Property Type:</label>
        <select   select name="property_type" id="property_type" required>
            <option value="">Select Property Type</option>
            <option value="SPA" <?= isset($tenant) && $tenant['property_type'] === 'SPA' ? 'selected' : '' ?>>SPA</option>
            <option value="MALL" <?= isset($tenant) && $tenant['property_type'] === 'MALL' ? 'selected' : '' ?>>MALL</option>
            <option value="HOTEL" <?= isset($tenant) && $tenant['property_type'] === 'HOTEL' ? 'selected' : '' ?>>HOTEL</option>
            <option value="SHOWROOM" <?= isset($tenant) && $tenant['property_type'] === 'SHOWROOM' ? 'selected' : '' ?>>SHOWROOM</option>
            <option value="HOUSE" <?= isset($tenant) && $tenant['property_type'] === 'HOUSE' ? 'selected' : '' ?>>HOUSE</option>
        </select>

        <label for="property_location">Location:</label>
        <input type="text" name="property_location" id="property_location" value="<?= isset($tenant) ? $tenant['property_location'] : '' ?>" required>

        <label for="property_owners">Owner:</label>
        <input type="text" name="property_owners" id="property_owners" value="<?= isset($tenant) ? $tenant['property_owners'] : '' ?>" required>

        <label for="monthly_rent">Monthly Rent:</label>
        <input type="text" name="monthly_rent" id="monthly_rent" value="<?= isset($tenant) ? $tenant['monthly_rent'] : '' ?>" required>

        <label for="deposit">Deposit:</label>
        <input type="text" name="deposit" id="deposit" value="<?= isset($tenant) ? $tenant['deposit'] : '' ?>" required>

        <label for="rent_status">Rent Status:</label>
        <select   select name="rent_status" id="rent_status" required>
            <option value="">Select Rent Status</option>
            <option value="Paid" <?= isset($tenant) && $tenant['rent_status'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
            <option value="Pending" <?= isset($tenant) && $tenant['rent_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        </select>

        <button type="submit">Submit</button>
    </form>

</body>
</html>
