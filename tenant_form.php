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
    $tenant_name = $address = $mobile_no = $email = $property_name = $property_type = $property_location = $property_owners = $monthly_rent = $deposit = $rent_status = $amount_paid = $amount_pending = $payment_mode = $transaction_details = $bank_name = $property_area = $electricity_provider = $rent_pay_date = '';
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
    $property_area = $_POST['property_area'];
    $electricity_provider = $_POST['electricity_provider'];
    $rent_pay_date = !empty($_POST['rent_pay_date']) ? $_POST['rent_pay_date'] : NULL;

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
                rent_status = ?,
                amount_paid = ?,
                amount_pending = ?,
                payment_mode = ?,
                transaction_details = ?,
                bank_name = ?,
                property_area = ?,
                electricity_provider = ?,
                rent_pay_date = ?
            WHERE id = ?"
        );

        // Corrected type string to include 12 variables
        $stmt->bind_param(
            "ssssssssddsddsssdsdi",
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
            $amount_paid,
            $amount_pending,
            $payment_mode,
            $transaction_details,
            $bank_name,
            $property_area,
            $electricity_provider,
            $rent_pay_date,
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
        $stmt = $conn->prepare("INSERT INTO tenants (tenant_name, address, mobile_no, email, property_name, property_type, property_location, property_owners, monthly_rent, deposit, rent_status, amount_paid, amount_pending, payment_mode, transaction_details, bank_name, property_area, electricity_provider, rent_pay_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssddsddsssdsd", 
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
        $amount_paid, 
        $amount_pending, 
        $payment_mode, 
        $transaction_details, 
        $bank_name, 
        $property_area, 
        $electricity_provider, 
        $rent_pay_date,);

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
    <style>
        /* Base Styles */
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

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .back-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            outline: none;
            width: 100%; /* Use full width to be responsive */
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #007bff;
        }

        .form-group {
            flex: 1 1 calc(50% - 20px);
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            align-self: center;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            transition: 0.3s;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
            border-bottom: 1px solid #444;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        .hamburger {
            font-size: 30px;
            cursor: pointer;
            display: none;
            margin-bottom: 20px;
        }

        /* Media Queries for Mobile Devices */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            .form-group {
                flex: 1 1 100%; /* Use full width on smaller screens */
            }
            button {
                width: 100%; /* Make the button full width */
            }
            .hamburger {
                display: block;
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
    

    <!-- Back Button -->
    <a href="welcome.php" class="back-button">Back</a>
        <h2>Tenant Form</h2>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <!-- Hidden field for editing -->
            <?php if (isset($tenant)): ?>
                <input type="hidden" name="id" value="<?= $tenant['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="tenant_name">Tenant Name:</label>
                <input type="text" name="tenant_name" id="tenant_name" value="<?= isset($tenant) ? $tenant['tenant_name'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?= isset($tenant) ? $tenant['address'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="mobile_no">Contact:</label>
                <input type="text" name="mobile_no" id="mobile_no" value="<?= isset($tenant) ? $tenant['mobile_no'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?= isset($tenant) ? $tenant['email'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="property_name">Property:</label>
                <input type="text" name="property_name" id="property_name" value="<?= isset($tenant) ? $tenant['property_name'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="property_type">Property Type:</label>
                <select name="property_type" id="property_type" required>
                    <option value="">Select Property Type</option>
                    <option value="SPA" <?= isset($tenant) && $tenant['property_type'] === 'SPA' ? 'selected' : '' ?>>SPA</option>
                    <option value="MALL" <?= isset($tenant) && $tenant['property_type'] === 'MALL' ? 'selected' : '' ?>>MALL</option>
                    <option value="HOTEL" <?= isset($tenant) && $tenant['property_type'] === 'HOTEL' ? 'selected' : '' ?>>HOTEL</option>
                    <option value="SHOWROOM" <?= isset($tenant) && $tenant['property_type'] === 'SHOWROOM' ? 'selected' : '' ?>>SHOWROOM</option>
                    <option value="HOUSE" <?= isset($tenant) && $tenant['property_type'] === 'HOUSE' ? 'selected' : '' ?>>HOUSE</option>
                </select>
            </div>

            <div class="form-group">
                <label for="property_location">Location:</label>
                <input type="text" name="property_location" id="property_location" value="<?= isset($tenant) ? $tenant['property_location'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="property_owners">Owner:</label>
                <input type="text" name="property_owners" id="property_owners" value="<?= isset($tenant) ? $tenant['property_owners'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="monthly_rent">Monthly Rent:</label>
                <input type="text" name="monthly_rent" id="monthly_rent" value="<?= isset($tenant) ? $tenant['monthly_rent'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="deposit">Deposit:</label>
                <input type="text" name="deposit" id="deposit" value="<?= isset($tenant) ? $tenant['deposit'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="rent_status">Rent Status:</label>
                <select name="rent_status" id="rent_status" required>
                    <option value="">Select Rent Status</option>
                    <option value="Rent Paid" <?= isset($tenant) && $tenant['rent_status'] === 'Rent Paid' ? 'selected' : '' ?>>Rent Paid</option>
                    <option value="Rent Pending" <?= isset($tenant) && $tenant['rent_status'] === 'Rent Pending' ? 'selected' : '' ?>>Rent Pending</option>
                    <option value="Deposit Paid" <?= isset($tenant) && $tenant['rent_status'] === 'Paid' ? 'selected' : '' ?>>Deposit Paid</option>
                    <option value="Deposit Pending" <?= isset($tenant) && $tenant['rent_status'] === 'Deposit Pending' ? 'selected' : '' ?>>Deposit Pending</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount_paid">Paid Amount:</label>
                <input type="text" name="amount_paid" id="amount_paid" value="<?= isset($tenant) ? $tenant['amount_paid'] : '' ?>">
            </div>

            <div class="form-group">
                <label for="amount_pending">Pending Amount:</label>
                <input type="text" name="amount_pending" id="amount_pending" value="<?= isset($tenant) ? $tenant['amount_pending'] : '' ?>">
            </div>

            <div class="form-group">
                <label for="payment_mode">Payment Mode:</label>
                <select name="payment_mode" id="payment_mode" required>
                    <option value="">Select Payment Mode</option>
                    <option value="Online" <?= isset($tenant) && $tenant['payment_mode'] === 'Online' ? 'selected' : '' ?>>Online</option>
                    <option value="Cash" <?= isset($tenant) && $tenant['payment_mode'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
                </select>
            </div>

            <div class="form-group">
                <label for="transaction_details">Transaction Details:</label>
                <input type="text" name="transaction_details" id="transaction_details" value="<?= isset($tenant) ? $tenant['transaction_details'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="bank_name">Bank Name:</label>
                <input type="text" name="bank_name" id="bank_name" value="<?= isset($tenant) ? $tenant['bank_name'] : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="property_area">Property Area:</label>
                <input type="text" name="property_area" id="property_area" value="<?= isset($tenant) ? $tenant['property_area'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="electricity_provider">Electricity Provider:</label>
                <select name="electricity_provider" id="electricity_provider" required>
                    <option value="">Select Electricity Provider</option>
                    <option value="Adani" <?= isset($tenant) && $tenant['electricity_provider'] === 'Adani' ? 'selected' : '' ?>>Adani</option>
                    <option value="MACB" <?= isset($tenant) && $tenant['electricity_provider'] === 'MACB' ? 'selected' : '' ?>>MACB</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rent_pay_date">Rent Pay Date:</label>
                <input type="date" name="rent_pay_date" id="rent_pay_date" value="<?= isset($tenant) ? $tenant['rent_pay_date'] : '' ?>" required>
            </div>


            <button type="submit" name="submit">Submit</button>
        </form>
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
