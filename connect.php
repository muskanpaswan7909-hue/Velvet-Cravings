<?php
/* connect.php - Final Universal attempt */
mysqli_report(MYSQLI_REPORT_OFF);

$is_localhost = ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1');

if ($is_localhost) {
    $servername = "127.0.0.1"; // TCP connection instead of socket
    $database   = "velvet_cravings";
    
    // Attempt 1: Default XAMPP (root, no password)
    $conn = @mysqli_connect($servername, "root", "", $database);
    
    // Attempt 2: Common Alterantive (root, root)
    if(!$conn) $conn = @mysqli_connect($servername, "root", "root", $database);

    // Attempt 3: Create Database if it's missing (without DB name first)
    if(!$conn) {
        $conn = @mysqli_connect($servername, "root", "");
        if(!$conn) $conn = @mysqli_connect($servername, "root", "root");
        
        if($conn) {
            mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
            mysqli_select_db($conn, $database);
        }
    }
} else {
    // Online InfinityFree Details ($servername, $username, $password, $database)
    $conn = @mysqli_connect("sql306.infinityfree.com", "if0_41570775", "12Paswan34", "if0_41570775_velvet");
}

if (!$conn) {
    echo "<h3>Access Denied: Please check your XAMPP Root Password!</h3>";
    echo "Go to <b>phpMyAdmin</b> -> <b>User Accounts</b> and see if 'root' has a password.<br>";
    echo "Error: " . mysqli_connect_error();
    die();
}
?>