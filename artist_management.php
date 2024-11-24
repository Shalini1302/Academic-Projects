<?php
session_start();
include 'db_connection.php'; // Include database connection

// Handle Add Artist
if (isset($_POST['add_artist'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $profile_picture = mysqli_real_escape_string($conn, $_POST['profile_picture']);

    $query = "INSERT INTO artists (name, bio, profile_picture) VALUES ('$name', '$bio', '$profile_picture')";
    mysqli_query($conn, $query);
    header("Location: artist_management.php");
    exit();
}

// Handle Update Artist
if (isset($_POST['edit_artist'])) {
    $id = intval($_POST['artist_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $profile_picture = mysqli_real_escape_string($conn, $_POST['profile_picture']);

    $query = "UPDATE artists SET name = '$name', bio = '$bio', profile_picture = '$profile_picture' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: artist_management.php");
    exit();
}

// Handle Delete Artist
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $query = "DELETE FROM artists WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: artist_management.php");
    exit();
}

// Fetch All Artists
$artists = mysqli_query($conn, "SELECT * FROM artists ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Management</title>
    <style>
        /* Add some basic styles */
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
    <h1>Artist Management</h1>

    <!-- Add Artist Form -->
    <form action="artist_management.php" method="POST">
        <h2>Add New Artist</h2>
        <input type="text" name="name" placeholder="Artist Name" required>
        <textarea name="bio" placeholder="Artist Biography" required></textarea>
        <input type="text" name="profile_picture" placeholder="Profile Picture URL" required>
        <button type="submit" name="add_artist">Add Artist</button>
    </form>

    <!-- Display Artists -->
    <h2>Existing Artists</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Bio</th>
                <th>Profile Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($artist = mysqli_fetch_assoc($artists)): ?>
                <tr>
                    <td><?php echo $artist['id']; ?></td>
                    <td><?php echo $artist['name']; ?></td>
                    <td><?php echo $artist['bio']; ?></td>
                    <td><img src="<?php echo $artist['profile_picture']; ?>" alt="Profile Picture" style="width: 80px;"></td>
                    <td>
                        <!-- Edit Form -->
                        <form action="artist_management.php" method="POST" style="display: inline-block;">
                            <input type="hidden" name="artist_id" value="<?php echo $artist['id']; ?>">
                            <input type="text" name="name" value="<?php echo $artist['name']; ?>" required>
                            <textarea name="bio"><?php echo $artist['bio']; ?></textarea>
                            <input type="text" name="profile_picture" value="<?php echo $artist['profile_picture']; ?>" required>
                            <button type="submit" name="edit_artist">Edit</button>
                        </form>
                        <!-- Delete Link -->
                        <a href="artist_management.php?delete_id=<?php echo $artist['id']; ?>" class="delete-link">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
