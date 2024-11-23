<?php
// Include your database connection file
include('db.php');
$query = "SELECT * FROM tenants";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    // Set the filename for the Excel (CSV) file
    $filename = "tenant_data_" . date('Y-m-d_H-i-s') . ".csv";
    
    // Open output stream to write the CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Open PHP output stream to write CSV to browser
    $output = fopen('php://output', 'w');
    
    // Write the column headers
    $headers = ['id', 'code', 'site_name', 'property_type', 'property_location', 'property_address',
    'pincode', 'property_owners', 'owners_details', 'tenant_name', 'address', 'mobile_no', 'email',
    'monthly_rent', 'deposit', 'maintenance_charges', 'online_paid', 'cash_paid', 'muncipal_tax',
    'cma_charges', 'electricity_charges', 'electricity_provider', 'water_charges', 'bank_no', 'bank_name',
    'bank_ifsc', 'bank_details', 'agreement_date', 'agreement_expiring', 'fitout_time', 'rent_start_date', 'increased_rent', 'agreement_years', 'yearly_escalation_percentage'];
    fputcsv($output, $headers);
    
    // Fetch and write each tenant row
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
} else {
    echo "No data available.";
}

$conn->close();
exit;
?>
