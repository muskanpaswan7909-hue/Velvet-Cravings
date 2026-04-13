<?php
/* create_table.php - Script to auto-create tables */
include 'connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 1. Create Customers Table
$sql1 = "CREATE TABLE IF NOT EXISTS `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(15) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// 2. Create Orders Table
$sql2 = "CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `product` VARCHAR(255) NOT NULL,
    `qty` INT NOT NULL DEFAULT 1,
    `address` TEXT NOT NULL,
    `status` VARCHAR(50) DEFAULT 'Pending',
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

echo "<h2>Database Setup Results:</h2>";

if (mysqli_query($conn, $sql1)) {
    echo "✔️ Customers table created OR already exists.<br>";
} else {
    echo "❌ Error creating customers table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $sql2)) {
    echo "✔️ Orders table created OR already exists.<br>";
} else {
    echo "❌ Error creating orders table: " . mysqli_error($conn) . "<br>";
}

echo "<br><b>Database is now READY! You can now Login and Register.</b>";
echo "<br><a href='index.html'>Go to Homepage</a>";
?>
