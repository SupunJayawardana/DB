<?php
// config.php
// Configuration details for the MySQL database connection.

$serverName = "localhost"; // Corrected typo from "locolhost"
$username   = "root";
$password   = "myposadminauthentication";
$database   = "uovt_database";

// Attempt to establish connection for MySQL.
// We use mysqli_connect instead of sqlsrv_connect.
$conn = mysqli_connect($serverName, $username, $password, $database);

// Optional: Set character set to UTF-8 to match your original config
if ($conn) {
    mysqli_set_charset($conn, "utf8mb4");
}


?>