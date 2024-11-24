<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT c.id as cart_id, a.title, a.artist_name, a.image_url, c.quantity 
          FROM cart c 
          JOIN artworks a ON c.artwork_id = a.id 
          WHERE c.user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="<?php echo $row['image_url']; ?>" alt="Artwork"></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['artist_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>
                        <a href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
