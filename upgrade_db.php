<?php
/* upgrade_db.php - Reliable Database Migration */
include 'connect.php';

if (!$conn) {
    die("❌ Connect Error: Ensure password is correct and DB exists in InfinityFree.");
}

// 1. Create customers table
$sql1 = "CREATE TABLE IF NOT EXISTS customers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql1)) {
    echo "✅ Customers table OK. ";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

// 2. Update orders table with status logic
$payCheck = mysqli_query($conn, "SHOW COLUMNS FROM `orders` LIKE 'status'");
if(mysqli_num_rows($payCheck) == 0) {
    mysqli_query($conn, "ALTER TABLE orders ADD status VARCHAR(50) DEFAULT 'Pending' AFTER address");
    echo "✅ Added status to orders. ";
}

echo "🎉 Database upgrade complete! Now you can register and order.";
?>
