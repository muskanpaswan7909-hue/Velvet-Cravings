<?php
header('Content-Type: application/json');
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Find the latest order for this phone
    $sql = "SELECT product, status, order_date FROM orders WHERE phone = '$phone' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(["status" => "success", "order_status" => $row['status'], "product" => $row['product'], "date" => $row['order_date']]);
    } else {
        echo json_encode(["status" => "error", "message" => "No order found for this number."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
