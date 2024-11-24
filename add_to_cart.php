<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in first.']);
    exit();
}

$email = $_SESSION['email'];
$item_id = $_POST['sale_id'] ?? $_POST['artwork_id'];
$item_type = isset($_POST['sale_id']) ? 'sale' : 'artwork';

// Check if item already exists in cart
$check_query = "SELECT * FROM cart_items WHERE user_email = ? AND artwork_id = ? AND item_type = ?";
$stmt = $conn->prepare($check_query);

if (!$stmt) {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database query preparation error']);
    exit();
}

$stmt->bind_param('sis', $email, $item_id, $item_type);

if (!$stmt->execute()) {
    error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database query execution error']);
    exit();
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing cart item
    $update_query = "UPDATE cart_items SET quantity = quantity + 1, updated_at = NOW() WHERE user_email = ? AND artwork_id = ? AND item_type = ?";
    $stmt = $conn->prepare($update_query);
    
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database query preparation error']);
        exit();
    }

    $stmt->bind_param('sis', $email, $item_id, $item_type);

    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database query execution error']);
        exit();
    }
} else {
    // Insert new cart item
    $insert_query = "INSERT INTO cart_items (user_email, artwork_id, quantity, created_at, updated_at, item_type) VALUES (?, ?, 1, NOW(), NOW(), ?)";
    $stmt = $conn->prepare($insert_query);
    
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Database query preparation error']);
        exit();
    }

    $stmt->bind_param('sis', $email, $item_id, $item_type);

    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database query execution error']);
        exit();
    }
}

// Get cart count
$count_query = "SELECT SUM(quantity) AS cart_count FROM cart_items WHERE user_email = ?";
$stmt = $conn->prepare($count_query);

if (!$stmt) {
    error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database query preparation error']);
    exit();
}

$stmt->bind_param('s', $email);

if (!$stmt->execute()) {
    error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database query execution error']);
    exit();
}

$count_result = $stmt->get_result();
$cart_count = $count_result->fetch_assoc()['cart_count'];

echo json_encode([
    'success' => true,
    'message' => 'Item added to cart successfully!',
    'cart_count' => $cart_count
]);
?>
