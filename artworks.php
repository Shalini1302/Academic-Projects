<?php
include 'db_connection.php'; // Include database connection

// Fetch All Artworks
$artworks = mysqli_query($conn, "SELECT * FROM artworks ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artworks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .artwork {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
        }
        .artwork img {
            max-width: 200px;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Artworks</h1>
    <?php while ($artwork = mysqli_fetch_assoc($artworks)): ?>
        <div class="artwork">
            <h2><?php echo $artwork['title']; ?></h2>
            <img src="<?php echo $artwork['image_url']; ?>" alt="Artwork">
            <p><?php echo $artwork['description']; ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>
