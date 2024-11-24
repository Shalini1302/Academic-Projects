<?php
session_start();
include 'db_connection.php'; // Include the database connection

// Handle Add Sale
if (isset($_POST['add_sale'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist_name = mysqli_real_escape_string($conn, $_POST['artist_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    $query = "INSERT INTO sales (title, artist_name, description, price, image_url) VALUES ('$title', '$artist_name', '$description', $price, '$image_url')";
    mysqli_query($conn, $query);
    header("Location: sales_management.php");
    exit();
}

// Handle Update Sale
if (isset($_POST['edit_sale'])) {
    $id = intval($_POST['sale_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist_name = mysqli_real_escape_string($conn, $_POST['artist_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    $query = "UPDATE sales SET title = '$title', artist_name = '$artist_name', description = '$description', price = $price, image_url = '$image_url' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: sales_management.php");
    exit();
}

// Handle Delete Sale
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $query = "DELETE FROM sales WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: sales_management.php");
    exit();
}

// Fetch All Sales
$sales = mysqli_query($conn, "SELECT * FROM sales ORDER BY price DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin: 20px auto;
            width: 80%;
        }
        input, textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .delete-link {
            color: red;
            text-decoration: none;
        }
        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Sales Management</h1>

    <!-- Add Sale Form -->
    <form action="sales_management.php" method="POST">
        <h2>Add New Sale</h2>
        <input type="text" name="title" placeholder="Sale Title" required>
        <input type="text" name="artist_name" placeholder="Artist Name" required>
        <textarea name="description" placeholder="Sale Description" required></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <button type="submit" name="add_sale">Add Sale</button>
    </form>

    <!-- Display Sales -->
    <h2>Existing Sales</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sale = mysqli_fetch_assoc($sales)): ?>
                <tr>
                    <td><?php echo $sale['id']; ?></td>
                    <td><?php echo $sale['title']; ?></td>
                    <td><?php echo $sale['artist_name']; ?></td>
                    <td><?php echo $sale['description']; ?></td>
                    <td>INR <?php echo number_format($sale['price'], 2); ?></td>
                    <td><img src="<?php echo $sale['image_url']; ?>" alt="Sale Image" style="width: 80px;"></td>
                    <td>
                        <!-- Edit Form -->
                        <form action="sales_management.php" method="POST" style="display: inline-block;">
                            <input type="hidden" name="sale_id" value="<?php echo $sale['id']; ?>">
                            <input type="text" name="title" value="<?php echo $sale['title']; ?>" required>
                            <input type="text" name="artist_name" value="<?php echo $sale['artist_name']; ?>" required>
                            <textarea name="description"><?php echo $sale['description']; ?></textarea>
                            <input type="number" step="0.01" name="price" value="<?php echo $sale['price']; ?>" required>
                            <input type="text" name="image_url" value="<?php echo $sale['image_url']; ?>" required>
                            <button type="submit" name="edit_sale">Edit</button>
                        </form>
                        <!-- Delete Link -->
                        <a href="sales_management.php?delete_id=<?php echo $sale['id']; ?>" class="delete-link">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
