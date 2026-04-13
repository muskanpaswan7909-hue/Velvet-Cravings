<?php
include "connect.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input Data to prevent SQL Injection
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $product = mysqli_real_escape_string($conn, trim($_POST['product']));
    $qty = (int)$_POST['qty'];
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    if (empty($name) || empty($phone) || empty($address)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit;
    }

    $query = "INSERT INTO orders (customer_name, phone, product, quantity, address) 
              VALUES ('$name', '$phone', '$product', '$qty', '$address')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Order Saved Successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}

mysqli_close($conn);
?>