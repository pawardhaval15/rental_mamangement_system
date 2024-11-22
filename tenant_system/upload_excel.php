<?php
session_start(); 
include('db.php');  

// Handle the file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excelFile'])) {
    $file = $_FILES['excelFile'];

    // Output the received file name to the browser console (For Debugging)
    echo "<script>console.log('Received file: " . addslashes($file['name']) . "');</script>";

    // Check if the file is a CSV and also validate file size if needed
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileExtension == 'csv') {

        // Ensure the uploads directory exists
        $uploadDirectory = 'uploads/';
        if (!is_dir($uploadDirectory)) {
            echo "<script>console.log('Uploads directory does not exist.');</script>";
            die("Error: The uploads directory does not exist.");
        }

        // Move the uploaded file to a temporary directory
        $tempFilePath = $uploadDirectory . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $tempFilePath)) {

            // Output the file upload path to the browser console (For Debugging)
            echo "<script>console.log('File uploaded to: " . addslashes($tempFilePath) . "');</script>";

            // Initialize counters for success and failure
            $successCount = 0;
            $failureCount = 0;

            // Open the CSV file and read
            if (($handle = fopen($tempFilePath, "r")) !== FALSE) {

                // Output to console that CSV is opened (For Debugging)
                echo "<script>console.log('CSV file opened successfully.');</script>";

                // Skip the header row (if it exists)
                $header = fgetcsv($handle);
                echo "<script>console.log('Skipped header: " . addslashes(implode(",", $header)) . "');</script>";

                // Process the rows and insert data into the database
                while (($data = fgetcsv($handle)) !== FALSE) {
                    // Log the row data to the console (For Debugging)
                    echo "<script>console.log('Processing row: " . addslashes(implode(",", $data)) . "');</script>";

                    // Ensure the row has valid data (e.g., 10 columns)
                    if (count($data) >= 19) {
                        // Prepare the SQL query to insert data
                        $stmt = $conn->prepare("INSERT INTO tenants
                        (tenant_name, address, mobile_no, email, property_name, property_type, property_location, property_owners, monthly_rent, deposit, rent_status, amount_paid, amount_pending, payment_mode, transaction_details, bank_name, property_area, electricity_provider, rent_pay_date)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ");

                        if ($stmt) {
                            // Bind the parameters and execute the query
                            $stmt->bind_param("ssssssssddsddsssdsd", // Corrected the data types
                            $data[1], // tenant_name
                            $data[2], // address
                            $data[3], // mobile_no
                            $data[4], // email
                            $data[5], // property_name
                            $data[6], // property_type
                            $data[7], // property_location
                            $data[8], // property_owners
                            $data[9], // monthly_rent
                            $data[10], // deposit
                            $data[11], // rent_status
                            $data[12], // amount_paid
                            $data[13], // amount_pending
                            $data[14], // payment_mode
                            $data[15], // transaction_details
                            $data[16], // bank_name
                            $data[17], // property_area
                            $data[18], // electricity_provider
                            $data[19]
                        );



                            if ($stmt->execute()) {
                                $successCount++;  // Increment success counter
                                echo "<script>console.log('Row inserted successfully.');</script>";
                            } else {
                                $failureCount++;  // Increment failure counter
                                echo "<script>console.log('Failed to insert row: " . addslashes($stmt->error) . "');</script>";
                            }
                            $stmt->close();
                        } else {
                            $failureCount++;  // Increment failure counter if the statement could not be prepared
                            echo "<script>console.log('Failed to prepare the statement.');</script>";
                        }
                    } else {
                        $failureCount++;  // Increment failure counter if the row is invalid
                        echo "<script>console.log('Invalid row data: " . addslashes(implode(",", $data)) . "');</script>";
                    }
                }

                fclose($handle);  // Close the file after processing
                unlink($tempFilePath);  // Delete the temporary file

                // Store the counts in the session
                $_SESSION['upload_status'] = [
                    'success' => $successCount,
                    'failure' => $failureCount
                ];

                // Output final success/failure counts to the console (For Debugging)
                echo "<script>console.log('Upload finished: $successCount success, $failureCount failure.');</script>";

                // Redirect back to manager.php to show the result
                header("Location: manager.php");
                exit();  // Ensure no further code is executed after redirection

            } else {
                // Log error if the CSV file can't be opened
                echo "<script>console.log('Failed to open the CSV file.');</script>";
                $_SESSION['upload_status'] = [
                    'success' => 0,
                    'failure' => 1
                ];
                header("Location: manager.php");
                exit();
            }
        } else {
            // Log error if file move failed
            echo "<script>console.log('Failed to move uploaded file to the uploads directory.');</script>";
            $_SESSION['upload_status'] = [
                'success' => 0,
                'failure' => 1
            ];
            header("Location: manager.php");
            exit();
        }
    } else {
        // Handle invalid file type
        echo "<script>console.log('Invalid file type: $fileExtension');</script>";
        $_SESSION['upload_status'] = [
            'success' => 0,
            'failure' => 1
        ];
        header("Location: manager.php");
        exit();
    }
}
?>
