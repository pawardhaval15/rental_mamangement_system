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
    $headers = ['ID', 'Tenant Name', 'Address', 'Mobile No', 'Email', 'Property Name', 'Property Type', 'Property Location', 'Property Owner', 'Monthly Rent', 'Deposit'];
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