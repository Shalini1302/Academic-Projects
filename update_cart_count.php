<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']); // Total quantity of items in the cart
    echo json_encode(['success' => true, 'count' => $cartCount]);
} else {
    echo json_encode(['success' => true, 'count' => 0]); // No items in the cart
}
?>
