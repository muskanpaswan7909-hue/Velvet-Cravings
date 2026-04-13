<?php
include 'connect.php';
header('Content-Type: text/plain');

if (!$conn) {
    die("❌ Connection Error: " . mysqli_connect_error());
} else {
    echo "✅ Database connection is SUCCESSFUL!\n";
}

// Check for table 'customers'
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'customers'");
if(mysqli_num_rows($table_check) > 0) {
    echo "✅ 'customers' table exists!\n";
} else {
    echo "❌ 'customers' table IS MISSING! Please run upgrade_db.php once via browser.\n";
}
?>
