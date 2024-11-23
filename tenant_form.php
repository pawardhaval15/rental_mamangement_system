<?php
include 'db.php';
$message = '';

// Check if tenant ID is provided for editing
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Use intval to ensure ID is numeric
    $stmt = $conn->prepare("SELECT * FROM tenants WHERE id = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tenant = $result->fetch_assoc();
    } else {
        $message = "<p style='color: red;'>No tenant found with this ID.</p>";
    }
    $stmt->close();
} else {
    // Initialize tenant data variables for the form
    $code = $site_name = $property_type = $property_location = $property_address = $pincode = $property_owners = $owners_details = $tenant_name = $address = $mobile_no = $email = $monthly_rent = $deposit = $maintenance_charges = $online_paid = $cash_paid = $muncipal_tax = $cma_charges = $electricity_charges = $electricity_provider = $water_charges = $bank_no = $bank_name = $bank_details = $agreement_date = $agreement_expiring = $fitout_time = $rent_start_date = $increased_rent = $agreement_years = $yearly_escalation_percentage = '';
}

// Handle form submission for insert/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $code = $_POST['code'] ?? '';
    $site_name = $_POST['site_name'] ?? '';
    $property_type = $_POST['property_type'] ?? '';
    $property_location = $_POST['property_location'] ?? '';
    $property_address = $_POST['property_address'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $property_owners = $_POST['property_owners'] ?? '';
    $owners_details = $_POST['owners_details'] ?? '';
    $tenant_name = $_POST['tenant_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $mobile_no = $_POST['mobile_no'] ?? '';
    $email = $_POST['email'] ?? '';
    $monthly_rent = $_POST['monthly_rent'] ?? 0;
    $deposit = $_POST['deposit'] ?? 0;
    $maintenance_charges = $_POST['maintenance_charges'] ?? 0;
    $online_paid = $_POST['online_paid'] ?? 0;
    $cash_paid = $_POST['cash_paid'] ?? 0;
    $munciple_tax = $_POST['munciple_tax'] ?? 0;
    $cma_charges = $_POST['cma_charges'] ?? 0;
    $electricity_charges = $_POST['electricity_charges'] ?? 0;
    $electricity_provider = $_POST['electricity_provider'] ?? '';
    $water_charges = $_POST['water_charges'] ?? 0;
    $bank_no = $_POST['bank_no'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $bank_ifsc = $_POST['bank_ifsc'] ?? '';
    $bank_details = $_POST['bank_details'] ?? '';
    $agreement_date = $_POST['agreement_date'] ?? '';
    $agreement_expiring = $_POST['agreement_expiring'] ?? '';
    $fitout_time = $_POST['fitout_time'] ?? '';
    $rent_start_date = $_POST['rent_start_date'] ?? '';
    $increased_rent = $_POST['increased_rent'] ?? '';
    $agreement_years = $_POST['agreement_years'] ?? '';
    $yearly_escalation_percentage = $_POST['yearly_escalation_percentage'] ?? '';

    if ($id) {
        // Update existing tenant
        $stmt = $conn->prepare(
            "UPDATE tenants
             SET code = ?, site_name = ?, property_type = ?, property_location = ?, property_address = ?,
                 pincode = ?, property_owners = ?, owners_details = ?, tenant_name = ?, address = ?,
                 mobile_no = ?, email = ?, monthly_rent = ?, deposit = ?, maintenance_charges = ?,
                 online_paid = ?, cash_paid = ?, muncipal_tax = ?, cma_charges = ?, electricity_charges = ?,
                 electricity_provider = ?, water_charges = ?, bank_no = ?, bank_name = ?, bank_ifsc = ?, bank_details = ?,
                 agreement_date = ?, agreement_expiring = ?, fitout_time = ?, rent_start_date = ?, increased_rent = ?, agreement_years = ?, yearly_escalation_percentage = ?
             WHERE id = ?"
        );
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param(
            "ssssssssssssddddddssssdsssssssddsi",
            $code, $site_name, $property_type, $property_location, $property_address,
            $pincode, $property_owners, $owners_details, $tenant_name, $address,
            $mobile_no, $email, $monthly_rent, $deposit, $maintenance_charges, $online_paid,
            $cash_paid, $muncipal_tax, $cma_charges, $electricity_charges, $electricity_provider,
            $water_charges, $bank_no, $bank_name, $bank_ifsc, $bank_details, $agreement_date,
            $agreement_expiring, $fitout_time, $rent_start_date, $increased_rent, $agreement_years,
            $yearly_escalation_percentage, $id
        );

        if ($stmt->execute()) {
            $message = "<p style='color: green;'>Tenant details updated successfully!</p>";
        } else {
            $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        // Insert new tenant
        $stmt = $conn->prepare(
            "INSERT INTO tenants (code, site_name, property_type, property_location, property_address,
            pincode, property_owners, owners_details, tenant_name, address, mobile_no, email,
            monthly_rent, deposit, maintenance_charges, online_paid, cash_paid, muncipal_tax,
            cma_charges, electricity_charges, electricity_provider, water_charges, bank_no, bank_name,
            bank_ifsc, bank_details, agreement_date, agreement_expiring, fitout_time, rent_start_date, increased_rent, agreement_years, yearly_escalation_percentage)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param(
            "ssssssssssssddddddssssdsssssssdds",
            $code, $site_name, $property_type, $property_location, $property_address,
            $pincode, $property_owners, $owners_details, $tenant_name, $address,
            $mobile_no, $email, $monthly_rent, $deposit, $maintenance_charges, $online_paid,
            $cash_paid, $muncipal_tax, $cma_charges, $electricity_charges, $electricity_provider,
            $water_charges, $bank_no, $bank_name, $bank_ifsc, $bank_details, $agreement_date,
            $agreement_expiring, $fitout_time, $rent_start_date, $increased_rent, $agreement_years,
            $yearly_escalation_percentage
        );

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
                <h2>Tenant Form</h2>
                <a href="welcome.php" class="btn btn-primary mb-3">Back</a>
                <!-- Message Section -->
                <?php if ($message): ?>
                    <div class="alert alert-info"><?= $message; ?></div>
                <?php endif; ?>
                <!-- Tenant Form -->
                <form method="POST" class="row g-3">
                    <?php if (isset($tenant)): ?>
                        <input type="hidden" name="id" value="<?= $tenant['id'] ?>">
                    <?php endif; ?>
                    <!-- Property Details Section -->
                    <h3 class="mt-4">Property Details</h3>
                    <div class="col-md-3">
                        <label for="code" class="form-label">CODE</label>
                        <input type="text" name="code" id="code" class="form-control" value="<?= isset($tenant) ? $tenant['code'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="site_name" class="form-label">SITE NAME</label>
                        <input type="text" name="site_name" id="site_name" class="form-control" value="<?= isset($tenant) ? $tenant['site_name'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="property_type" class="form-label">SITE TYPE</label>
                        <select name="property_type" id="property_type" class="form-select" required>
                            <option value="" selected disabled>Select Property Type</option>
                            <option value="SPA" <?= isset($tenant) && $tenant['property_type'] === 'SPA' ? 'selected' : '' ?>>SPA</option>
                            <option value="MALL" <?= isset($tenant) && $tenant['property_type'] === 'MALL' ? 'selected' : '' ?>>MALL</option>
                            <option value="HOTEL" <?= isset($tenant) && $tenant['property_type'] === 'HOTEL' ? 'selected' : '' ?>>HOTEL</option>
                            <option value="SHOWROOM" <?= isset($tenant) && $tenant['property_type'] === 'SHOWROOM' ? 'selected' : '' ?>>SHOWROOM</option>
                            <option value="HOUSE" <?= isset($tenant) && $tenant['property_type'] === 'HOUSE' ? 'selected' : '' ?>>HOUSE</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="property_location" class="form-label">SITE LOCATION</label>
                        <input type="text" name="property_location" id="property_location" class="form-control" value="<?= isset($tenant) ? $tenant['property_location'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="property_address" class="form-label">SITE ADDRESS</label>
                        <input type="text" name="property_address" id="property_address" class="form-control" value="<?= isset($tenant) ? $tenant['property_address'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="pincode" class="form-label">PINCODE</label>
                        <input type="text" name="pincode" id="pincode" class="form-control" value="<?= isset($tenant) ? $tenant['pincode'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="property_owners" class="form-label">LANDLORD NAME</label>
                        <input type="text" name="property_owners" id="property_owners" class="form-control" value="<?= isset($tenant) ? $tenant['property_owners'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="owners_details" class="form-label">LANDLORD DETAILS</label>
                        <input type="text" name="owners_details" id="owners_details" class="form-control" value="<?= isset($tenant) ? $tenant['owners_details'] : '' ?>">
                    </div>

                    <!-- Tenant Details Section -->
                    <h3 class="mt-4">Tenant Details</h3>
                    <div class="col-md-3">
                        <label for="tenant_name" class="form-label">TENANT NAME</label>
                        <input type="text" name="tenant_name" id="tenant_name" class="form-control" value="<?= isset($tenant) ? $tenant['tenant_name'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="address" class="form-label">ADDRESS</label>
                        <input type="text" name="address" id="address" class="form-control" value="<?= isset($tenant) ? $tenant['address'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mobile_no" class="form-label">CONTACT</label>
                        <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="<?= isset($tenant) ? $tenant['mobile_no'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">EMAIL</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= isset($tenant) ? $tenant['email'] : '' ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="monthly_rent" class="form-label">MONTHLY RENT</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="<?= isset($tenant) ? $tenant['monthly_rent'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="deposit" class="form-label">DEPOSIT</label>
                        <input type="number" name="deposit" id="deposit" class="form-control" value="<?= isset($tenant) ? $tenant['deposit'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="maintenance_charges" class="form-label">MAINTENANCE CHARGES</label>
                        <input type="number" name="maintenance_charges" id="maintenance_charges" class="form-control" value="<?= isset($tenant) ? $tenant['maintenance_charges'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="online_paid" class="form-label">ONLINE PAYMENT</label>
                        <input type="number" name="online_paid" id="online_paid" class="form-control" value="<?= isset($tenant) ? $tenant['online_paid'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="cash_paid" class="form-label">CASH PAYMENT</label>
                        <input type="number" name="cash_paid" id="cash_paid
                        " class="form-control" value="<?= isset($tenant) ? $tenant['cash_paid'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="muncipal_tax" class="form-label">MUNCIPAL TAX</label>
                        <input type="number" name="muncipal_tax" id="muncipal_tax
                        " class="form-control" value="<?= isset($tenant) ? $tenant['muncipal_tax'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="cma_charges" class="form-label">CMA CHARGES</label>
                        <input type="number" name="cma_charges" id="cma_charges
                        " class="form-control" value="<?= isset($tenant) ? $tenant['cma_charges'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="electricity_provider" class="form-label">SITE TYPE</label>
                        <select name="electricity_provider" id="electricity_provider" class="form-select" required>
                            <option value="" selected disabled>Select Property Type</option>
                            <option value="MECB" <?= isset($tenant) && $tenant['electricity_provider'] === 'MECB' ? 'selected' : '' ?>>MECB</option>
                            <option value="ADANI" <?= isset($tenant) && $tenant['electricity_provider'] === 'ADANI' ? 'selected' : '' ?>>ADANI</option>
                            <option value="Relaince" <?= isset($tenant) && $tenant['electricity_provider'] === 'Relaince' ? 'selected' : '' ?>>Relaince</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="electricity_charges" class="form-label">ELECTRICITY CHARGES</label>
                        <input type="number" name="electricity_charges" id="electricity_charges
                        " class="form-control" value="<?= isset($tenant) ? $tenant['electricity_charges'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="water_charges" class="form-label">WATER CHARGES</label>
                        <input type="number" name="water_charges" id="water_charges
                        " class="form-control" value="<?= isset($tenant) ? $tenant['water_charges'] : '' ?>">
                    </div>

                    <!-- Bank Details Section -->
                    <h3 class="mt-4">Bank Details</h3>
                    <div class="col-md-3">
                        <label for="bank_no" class="form-label">BANK No</label>
                        <input type="number" name="bank_no" id="bank_no
                        " class="form-control" value="<?= isset($tenant) ? $tenant['bank_no'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="bank_name" class="form-label">BANK NAME</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?= isset($tenant) ? $tenant['bank_name'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="bank_ifsc" class="form-label">BANK IFCS</label>
                        <input type="text" name="bank_ifsc" id="bank_ifsc" class="form-control" value="<?= isset($tenant) ? $tenant['bank_ifsc'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="bank_details" class="form-label">BANK DETAILS</label>
                        <input type="text" name="bank_details" id="bank_details" class="form-control" value="<?= isset($tenant) ? $tenant['bank_details'] : '' ?>" required>
                    </div>

                    <!-- Bank Details Section -->
                    <h3 class="mt-4">Agreement Details</h3>
                    <div class="col-md-3">
                        <label for="agreement_date" class="form-label">AGREEMENT DATE</label>
                        <input type="date" data-date="" date-date-format="DD MMMM YYYY" name="agreement_date" id="agreement_date"
                        class="form-control" value="<?= isset($tenant) ? $tenant['agreement_date'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="agreement_expiring" class="form-label">AGREEMENT EXPIRE</label>
                        <input type="date" data-date="" date-date-format="DD MMMM YYYY" name="agreement_expiring" id="agreement_expiring"
                        class="form-control" value="<?= isset($tenant) ? $tenant['agreement_expiring'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="fitout_time" class="form-label">FITOUT DATE</label>
                        <input type="date" data-date="" date-date-format="DD MMMM YYYY" name="fitout_time" id="fitout_time"
                        class="form-control" value="<?= isset($tenant) ? $tenant['fitout_time'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="rent_start_date" class="form-label">RENT PAY DATE</label>
                        <input type="date" data-date="" date-date-format="DD MMMM YYYY" name="rent_start_date" id="rent_start_date"
                        class="form-control" value="<?= isset($tenant) ? $tenant['rent_start_date'] : '' ?>">
                    </div>

                    <h3 class="mt-4">Rent Escalation Calculator</h3>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="agreement_years" class="form-label">Agreement Years</label>
                            <select name="agreement_years" id="agreement_years" class="form-select">
                                <option value="1">1 Year</option>
                                <option value="2">2 Years</option>
                                <option value="3">3 Years</option>
                                <option value="4">4 Years</option>
                                <option value="5">5 Years</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="escalation_percentage" class="form-label">Yearly Escalation (%)</label>
                            <input type="number" name="escalation_percentage" id="escalation_percentage" class="form-control" min="0" max="100" step="0.1">
                        </div>

                        <div class="col-md-3">
                            <label for="increased_rent" class="form-label">Increased Rent</label>
                            <input type="number" name="increased_rent" id="increased_rent" class="form-control" step="0.01">
                        </div>

                        
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary form-control" onclick="calculateEscalation()">Calculate</button>
                        </div>
                    </div>

                    <div class="mt-4" id="escalationResults">
                        <h4>Escalation Schedule</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Monthly Rent</th>
                                        <th>Yearly Rent</th>
                                        <th>Increase Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="escalationTable">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Add this JavaScript before the closing body tag -->
                    <script>
                        function calculateEscalation() {
                            const baseRent = parseFloat(document.getElementById('monthly_rent').value);
                            const years = parseInt(document.getElementById('agreement_years').value);
                            const escalationRate = parseFloat(document.getElementById('escalation_percentage').value) / 100;
                            
                            if (!baseRent || isNaN(baseRent)) {
                                alert('Please enter a valid monthly rent');
                                return;
                            }

                            if (!escalationRate || isNaN(escalationRate)) {
                                alert('Please enter a valid escalation percentage');
                                return;
                            }

                            const tableBody = document.getElementById('escalationTable');
                            tableBody.innerHTML = ''; // Clear previous results

                            let currentRent = baseRent;
                            let previousRent = baseRent;
                            let increasedRent = 0;

                            for (let year = 1; year <= years; year++) {
                                const row = document.createElement('tr');
                                
                                if (year > 1) {
                                    currentRent = previousRent * (1 + escalationRate);
                                }

                                const yearlyRent = currentRent * 12;
                                const increaseAmount = currentRent - previousRent;

                                row.innerHTML = `
                                    <td>Year ${year}</td>
                                    <td>₹${currentRent.toFixed(2)}</td>
                                    <td>₹${yearlyRent.toFixed(2)}</td>
                                    <td>${year === 1 ? '-' : '₹' + increaseAmount.toFixed(2)}</td>
                                `;

                                tableBody.appendChild(row);
                                previousRent = currentRent;

                                // Store the increased rent for the last year
                                if (year === years) {
                                    increasedRent = currentRent;
                                }
                            }

                            // Set the increased rent in the form field (if required)
                            document.getElementById('increased_rent').value = increasedRent.toFixed(2);

                            document.getElementById('escalationResults').style.display = 'block';
                        }
                    </script>



                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
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