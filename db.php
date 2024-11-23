
<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "data_rental";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
