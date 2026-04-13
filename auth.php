<?php
/*auth.php - Core authentication & order history */
session_start();
header('Content-Type: application/json');

// Silencing warnings for JSON output
error_reporting(0);
include 'connect.php';

// Check if database connection exists
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database server down. Try after 5 mins.']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == 'register') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if table 'customers' exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'customers'");
    if(mysqli_num_rows($table_check) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'System error: Please run upgrade_db.php first.']);
        exit;
    }

    $check = mysqli_query($conn, "SELECT id FROM customers WHERE email='$email' OR phone='$phone'");
    if(mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email/Phone already registered! Try login.']);
        exit;
    }

    $sql = "INSERT INTO customers (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
    if(mysqli_query($conn, $sql)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Try again later.']);
    }
}
elseif ($action == 'login') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM customers WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if($row = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_phone'] = $row['phone'];
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No account found. Please Register.']);
    }
}
elseif ($action == 'check') {
    if(isset($_SESSION['user_id'])) {
        echo json_encode([
            'logged_in' => true, 
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'phone' => $_SESSION['user_phone']
        ]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
}
elseif ($action == 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success']);
}
elseif ($action == 'history') {
    if(!isset($_SESSION['user_phone'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
        exit;
    }
    $phone = $_SESSION['user_phone'];
    $sql = "SELECT * FROM orders WHERE phone='$phone' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $orders = [];
    while($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $orders]);
}
elseif ($action == 'cancel_order') {
    if(!isset($_SESSION['user_phone'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
        exit;
    }
    $order_id = (int)$_POST['order_id'];
    $phone = $_SESSION['user_phone'];
    $check = mysqli_query($conn, "SELECT status FROM orders WHERE id=$order_id AND phone='$phone'");
    if($row = mysqli_fetch_assoc($check)) {
        if($row['status'] == 'Pending') {
            $sql = "UPDATE orders SET status='Cancelled' WHERE id=$order_id";
            if(mysqli_query($conn, $sql)) {
                echo json_encode(['status' => 'success', 'message' => 'Order cancelled!']);
            } else { echo json_encode(['status' => 'error', 'message' => 'SQL Error.']); }
        } else { echo json_encode(['status' => 'error', 'message' => 'Order already processed.']); }
    } else { echo json_encode(['status' => 'error', 'message' => 'Order not found.']); }
}
?>
