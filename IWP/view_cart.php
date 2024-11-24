<?php
session_start();
include 'db_connection.php';

// Enhanced security: Validate session
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

$query = "SELECT ci.*, 
          CASE 
            WHEN ci.item_type = 'artwork' THEN a.title 
            WHEN ci.item_type = 'sale' THEN s.title 
          END AS title,
          CASE 
            WHEN ci.item_type = 'artwork' THEN a.artist_name 
            WHEN ci.item_type = 'sale' THEN s.artist_name 
          END AS artist_name,
          CASE 
            WHEN ci.item_type = 'artwork' THEN a.description 
            WHEN ci.item_type = 'sale' THEN s.description 
          END AS description,
          CASE 
            WHEN ci.item_type = 'artwork' THEN a.image_url 
            WHEN ci.item_type = 'sale' THEN s.image_url 
          END AS image_url,
          CASE 
            WHEN ci.item_type = 'artwork' THEN a.price
            WHEN ci.item_type = 'sale' THEN s.price 
            ELSE NULL 
          END AS price
          FROM cart_items ci
          LEFT JOIN artworks a ON ci.artwork_id = a.id AND ci.item_type = 'artwork'
          LEFT JOIN sales s ON ci.artwork_id = s.id AND ci.item_type = 'sale'
          WHERE ci.user_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total cart value with explicit numeric conversion
$total_cart_value = 0;
foreach ($cart_items as $item) {
    $price = floatval($item['price'] ?? 0);
    $quantity = intval($item['quantity'] ?? 0);
    $total_cart_value += $price * $quantity;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        img { max-width: 100px; height: auto; }
        .btn { 
            padding: 5px 10px; 
            cursor: pointer; 
            margin: 0 5px;
        }
        .remove-btn { background-color: #ff4444; color: white; }
        .quantity-btn { background-color: #f0f0f0; }
        .total { font-weight: bold; text-align: right; }
        .proceed-btn { background-color: hotpink; color: white; text-align:right; }
    </style>
</head>
<body>
    <h1>Your Cart</h1>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $cart_item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cart_item['title']); ?></td>
                        <td><?php echo htmlspecialchars($cart_item['artist_name']); ?></td>
                        <td><?php echo htmlspecialchars($cart_item['description']); ?></td>
                        <?php $image_path = 'http://localhost/admin/' . htmlspecialchars($cart_item['image_url']); ?>
                        <td><img src="<?php echo $image_path; ?>" alt="Item Image"></td>
                        <td>INR <?php echo number_format(floatval($cart_item['price'] ?? 0), 2); ?></td>
                        <td>
                            <button class="btn quantity-btn decrement-quantity" 
                                    data-art-id="<?php echo $cart_item['artwork_id']; ?>" 
                                    data-type="<?php echo $cart_item['item_type']; ?>">-</button>
                            <span class="quantity"><?php echo $cart_item['quantity']; ?></span>
                            <button class="btn quantity-btn increment-quantity" 
                                    data-art-id="<?php echo $cart_item['artwork_id']; ?>" 
                                    data-type="<?php echo $cart_item['item_type']; ?>">+</button>
                        </td>
                        <td>INR <?php echo number_format(floatval($cart_item['price'] ?? 0) * intval($cart_item['quantity']), 2); ?></td>
                        <td>
                            <button class="btn remove-btn remove-from-cart" 
                                    data-art-id="<?php echo $cart_item['artwork_id']; ?>" 
                                    data-type="<?php echo $cart_item['item_type']; ?>">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="total">Total:</td>
                    <td colspan="2">INR <?php echo number_format($total_cart_value, 2); ?>
                    <form action="payment.php" method="get"> 
                    <input type="hidden" name="amount" value="<?php echo $total_cart_value; ?>"> 
                    <button type="submit" class="proceed-btn">Proceed →</button> </form></td>
                </tr>
             </tfoot>
        </table>
    <?php endif; ?>

    <script>
        $(document).on('click', '.increment-quantity', function() {
    var artId = $(this).data('art-id');
    var type = $(this).data('type');
    updateQuantity(artId, type, 'increment');
});

$(document).on('click', '.decrement-quantity', function() {
    var artId = $(this).data('art-id');
    var type = $(this).data('type');
    updateQuantity(artId, type, 'decrement');
});

function updateQuantity(artId, type, action) {
    $.ajax({
        url: 'update_cart.php',
        type: 'POST',
        data: { art_id: artId, type: type, action: action },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", xhr.status, xhr.responseText);
            alert("An error occurred. Please try again.");
        }
    });
}

        $(document).on('click', '.remove-from-cart', function() {
    var artId = $(this).data('art-id');
    var type = $(this).data('type');

    if (confirm("Are you sure you want to remove this item from the cart?")) {
        $.ajax({
            url: 'remove_from_cart.php',
            type: 'POST',
            data: { art_id: artId, type: type },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.status, xhr.responseText);
                alert("An error occurred. Please try again.");
            }
        });
    }
});
</script>
</body>
</html>