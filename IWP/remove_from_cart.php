<?php
session_start();
header('Content-Type: application/json');
include 'db_connection.php';

if (isset($_POST['art_id']) && isset($_POST['type']) && isset($_SESSION['email'])) {
    $item_id = $_POST['art_id'];
    $item_type = $_POST['type'];
    $email = $_SESSION['email']; // Assuming email is stored in session

    // Remove the item from the database using email
    $query = "DELETE FROM cart_items WHERE user_email = ? AND artwork_id = ? AND item_type = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('sis', $email, $item_id, $item_type);
        if ($stmt->execute()) {
            // Fetch the updated cart count
            $count_query = "SELECT SUM(quantity) AS cart_count FROM cart_items WHERE user_email = ?";
            if ($count_stmt = $conn->prepare($count_query)) {
                $count_stmt->bind_param('s', $email);
                if ($count_stmt->execute()) {
                    $count_result = $count_stmt->get_result();
                    $cart_count = $count_result->fetch_assoc()['cart_count'];
                    echo json_encode(['success' => true, 'message' => 'Item removed from cart successfully!', 'cart_count' => $cart_count]);
                    $count_stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error fetching updated cart count.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error preparing cart count query.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error removing item from cart.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing remove item query.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Item ID, item type, or user email not provided.']);
}

$conn->close();
?>
