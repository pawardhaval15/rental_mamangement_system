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
                    if (count($data) >= 33) {
                        // Prepare the SQL query to insert data
                        $stmt = $conn->prepare("INSERT INTO tenants
                        (code, site_name, property_type, property_location, property_address,
                        pincode, property_owners, owners_details, tenant_name, address, mobile_no, email,
                        monthly_rent, deposit, maintenance_charges, online_paid, cash_paid, muncipal_tax,
                        cma_charges, electricity_charges, electricity_provider, water_charges, bank_no, bank_name,
                        bank_ifsc, bank_details, agreement_date, agreement_expiring, fitout_time, rent_start_date, increased_rent, agreement_years, yearly_escalation_percentage)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?S)
                        ");

                        if ($stmt) {
                            // Bind the parameters and execute the query
                            $stmt->bind_param("ssssssssssssddddddssssdsssssssdds", // Corrected the data types
                            $data[1], 
                            $data[2], 
                            $data[3], 
                            $data[4], 
                            $data[5], 
                            $data[6], 
                            $data[7], 
                            $data[8], 
                            $data[9], 
                            $data[10], 
                            $data[11], 
                            $data[12], 
                            $data[13], 
                            $data[14], 
                            $data[15], 
                            $data[16], 
                            $data[17], 
                            $data[18], 
                            $data[19],
                            $data[20],
                            $data[21], 
                            $data[22], 
                            $data[23], 
                            $data[24], 
                            $data[25], 
                            $data[26], 
                            $data[27], 
                            $data[28], 
                            $data[29],
                            $data[30],
                            $data[31],
                            $data[32],
                            $data[33]
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


<!-- 
<php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excelFile'])) {
    $file = $_FILES['excelFile'];

    // Validate the uploaded file
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileExtension === 'xlsx') {

        // Ensure the uploads directory exists
        $uploadDirectory = 'uploads/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        // Move the uploaded file to the uploads directory
        $tempFilePath = $uploadDirectory . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $tempFilePath)) {

            // Extract the contents of the .xlsx file (which is a ZIP archive)
            $zip = new ZipArchive();
            if ($zip->open($tempFilePath) === TRUE) {
                $zip->extractTo($uploadDirectory . 'extracted/');
                $zip->close();

                // Load the extracted sheet data (usually `xl/sharedStrings.xml` and `xl/worksheets/sheet1.xml`)
                $sheetXMLPath = $uploadDirectory . 'extracted/xl/worksheets/sheet1.xml';
                $sharedStringsXMLPath = $uploadDirectory . 'extracted/xl/sharedStrings.xml';

                if (file_exists($sheetXMLPath) && file_exists($sharedStringsXMLPath)) {
                    // Load shared strings (contains cell values for strings)
                    $sharedStringsXML = simplexml_load_file($sharedStringsXMLPath);
                    $sharedStrings = [];
                    foreach ($sharedStringsXML->si as $stringItem) {
                        $sharedStrings[] = (string)$stringItem->t;
                    }

                    // Load the sheet data
                    $sheetXML = simplexml_load_file($sheetXMLPath);

                    $rows = $sheetXML->sheetData->row;
                    $successCount = 0;
                    $failureCount = 0;

                    foreach ($rows as $rowIndex => $row) {
                        // Skip the header (first row)
                        if ($rowIndex === 0) {
                            continue;
                        }

                        $rowData = [];
                        foreach ($row->c as $cell) {
                            $cellValue = isset($cell->v) ? (string)$cell->v : '';
                            $type = (string)$cell['t'];

                            if ($type === 's') {
                                // Shared string type
                                $rowData[] = $sharedStrings[(int)$cellValue];
                            } else {
                                $rowData[] = $cellValue;
                            }
                        }

                        // Ensure valid row data length
                        if (count($rowData) >= 33) {
                            $stmt = $conn->prepare("INSERT INTO tenants
                            (code, site_name, property_type, property_location, property_address,
                            pincode, property_owners, owners_details, tenant_name, address, mobile_no, email,
                            monthly_rent, deposit, maintenance_charges, online_paid, cash_paid, muncipal_tax,
                            cma_charges, electricity_charges, electricity_provider, water_charges, bank_no, bank_name,
                            bank_ifsc, bank_details, agreement_date, agreement_expiring, fitout_time, rent_start_date, increased_rent, agreement_years, yearly_escalation_percentage)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                            if ($stmt) {
                                $stmt->bind_param(
                                    "ssssssssssssddddddssssdsssssssdds",
                                    $rowData[0], $rowData[1], $rowData[2], $rowData[3], $rowData[4],
                                    $rowData[5], $rowData[6], $rowData[7], $rowData[8], $rowData[9],
                                    $rowData[10], $rowData[11], $rowData[12], $rowData[13], $rowData[14],
                                    $rowData[15], $rowData[16], $rowData[17], $rowData[18], $rowData[19],
                                    $rowData[20], $rowData[21], $rowData[22], $rowData[23], $rowData[24],
                                    $rowData[25], $rowData[26], $rowData[27], $rowData[28], $rowData[29],
                                    $rowData[30], $rowData[31]
                                );

                                if ($stmt->execute()) {
                                    $successCount++;
                                } else {
                                    $failureCount++;
                                }
                                $stmt->close();
                            } else {
                                $failureCount++;
                            }
                        } else {
                            $failureCount++;
                        }
                    }

                    // Clean up
                    unlink($tempFilePath); // Delete uploaded file
                    array_map('unlink', glob($uploadDirectory . 'extracted/*.*')); // Clear extracted files
                    rmdir($uploadDirectory . 'extracted/');

                    $_SESSION['upload_status'] = [
                        'success' => $successCount,
                        'failure' => $failureCount
                    ];
                    header("Location: manager.php");
                    exit();
                } else {
                    echo "Required XML files not found in the .xlsx file.";
                    exit();
                }
            } else {
                echo "Failed to extract the .xlsx file.";
                exit();
            }
        } else {
            echo "Failed to upload the file.";
            exit();
        }
    } else {
        echo "Invalid file type. Please upload an Excel (.xlsx) file.";
        exit();
    }
} else {
    echo "No file uploaded.";
    exit();
}
?> -->

