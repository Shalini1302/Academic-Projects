<?php
session_start();
include 'db_connection.php'; // Include database connection

// Handle Add Artwork
if (isset($_POST['add_artwork'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist_name = mysqli_real_escape_string($conn, $_POST['artist_name']); // Get artist name from form
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    // Insert artwork into the database, including artist_name
    $query = "INSERT INTO artworks (title, artist_name, description, image_url) VALUES ('$title', '$artist_name', '$description', '$image_url')";
    mysqli_query($conn, $query);
    header("Location: artwork_management.php");
    exit();
}

// Handle Delete Artwork
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $query = "DELETE FROM artworks WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: artwork_management.php");
    exit();
}

// Handle Edit Artwork
if (isset($_POST['edit_artwork'])) {
    $id = intval($_POST['artwork_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist_name = mysqli_real_escape_string($conn, $_POST['artist_name']); // Include artist_name when editing
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    // Update the artwork including artist_name
    $query = "UPDATE artworks SET title = '$title', artist_name = '$artist_name', description = '$description', price = '$price', image_url = '$image_url' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: artwork_management.php");
    exit();
}

// Fetch All Artworks
$artworks = mysqli_query($conn, "SELECT * FROM artworks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 70%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
        }
        input, textarea {
            width: 70%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Artwork Management</h1>

    <!-- Add Artwork Form -->
    <h2>Add New Artwork</h2>
    <form action="artwork_management.php" method="POST">
        <input type="text" name="title" placeholder="Artwork Title" required>
        <input type="text" name="artist_name" placeholder="Artist Name" required>
        <textarea name="description" placeholder="Artwork Description" required></textarea>
        <input type="text" name="price" placeholder="price" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <br>
        <button type="submit" name="add_artwork">Add Artwork</button>
    </form>

    <!-- Display Artworks -->
    <h2>Existing Artworks</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Artist Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($artwork = mysqli_fetch_assoc($artworks)): ?>
                <tr>
                    <td><?php echo $artwork['id']; ?></td>
                    <td><?php echo $artwork['title']; ?></td>
                    <td><?php echo $artwork['artist_name']; ?></td>
                    <td><?php echo $artwork['description']; ?></td>
                    <td><?php echo $artwork['price']; ?></td>
                    <td><img src="<?php echo $artwork['image_url']; ?>" alt="Artwork" style="width: 100px;"></td>
                    <td>
                        <form action="artwork_management.php" method="POST" style="display: inline;">
                            <input type="hidden" name="artwork_id" value="<?php echo $artwork['id']; ?>">
                            <input type="text" name="title" value="<?php echo $artwork['title']; ?>" required>
                            <input type="text" name="artist_name" value="<?php echo $artwork['artist_name']; ?>" required>
                            <textarea name="description" required><?php echo $artwork['description']; ?></textarea>
                            <input type="text" name="price" value="<?php echo $artwork['price']; ?>" required>
                            <input type="text" name="image_url" value="<?php echo $artwork['image_url']; ?>" required>
                            <button type="submit" name="edit_artwork">Edit</button>
                        </form>
                        <a href="artwork_management.php?delete_id=<?php echo $artwork['id']; ?>" 
                           style="color: red; text-decoration: none;">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
