<?php
session_start();
header('Content-Type: application/json');
include 'db_connection.php';

if (isset($_POST['art_id']) && isset($_POST['type']) && isset($_POST['action']) && isset($_SESSION['email'])) {
    $item_id = $_POST['art_id'];
    $item_type = $_POST['type'];
    $action = $_POST['action'];
    $email = $_SESSION['email'];

    // Check action and update quantity
    if ($action === 'increment') {
        $update_query = "UPDATE cart_items SET quantity = quantity + 1 WHERE user_email = ? AND artwork_id = ? AND item_type = ?";
    } elseif ($action === 'decrement') {
        $update_query = "UPDATE cart_items SET quantity = quantity - 1 WHERE user_email = ? AND artwork_id = ? AND item_type = ? AND quantity > 1";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit();
    }

    if ($stmt = $conn->prepare($update_query)) {
        $stmt->bind_param('sis', $email, $item_id, $item_type);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Fetch the updated cart count
            $count_query = "SELECT SUM(quantity) AS cart_count FROM cart_items WHERE user_email = ?";
            if ($count_stmt = $conn->prepare($count_query)) {
                $count_stmt->bind_param('s', $email);
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $cart_count = $count_result->fetch_assoc()['cart_count'];

                echo json_encode(['success' => true, 'message' => 'Cart updated successfully!', 'cart_count' => $cart_count]);
                $count_stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error preparing cart count query.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made to the cart.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing update query.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Item ID, item type, action, or user email not provided.']);
}

$conn->close();
?>
