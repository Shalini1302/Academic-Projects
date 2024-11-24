<?php
// Step 1: Establish Database Connection
$conn = new mysqli('localhost', 'root', '', 'artwork');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Get Artwork Details if artwork_id is provided
if (isset($_GET['artwork_id']) && !empty($_GET['artwork_id'])) {
    $artwork_id = $_GET['artwork_id'];
    $query = "SELECT * FROM artworks WHERE id = $artwork_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $artwork = $result->fetch_assoc();
    } else {
        echo "Artwork not found.";
        exit;
    }
} else {
    echo "No artwork ID provided.";
    exit;
}

// Step 3: Process Form Submission for Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image_url = $artwork['image_url']; // Default to existing image

    // Check if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image_url = $target_file; // Update image path
    }

    // Update artwork details in database
    $update_query = "UPDATE artworks SET title='$title', artist='$artist', category='$category', price='$price', image_url='$image_url' WHERE id=$artwork_id";
    
    if ($conn->query($update_query) === TRUE) {
        echo "Artwork updated successfully!";
        header("Location: admin_page.php"); // Redirect to admin page
        exit;
    } else {
        echo "Error updating artwork: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Artwork</title>
    <style>
        /* Basic styling for form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-family: cursive;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Update Artwork</h2>
    <form action="update_artwork.php?artwork_id=<?php echo $artwork['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Artwork Title:</label>
            <input type="text" name="title" value="<?php echo $artwork['title']; ?>" required>
        </div>
        <div class="form-group">
            <label for="artist">Artist:</label>
            <input type="text" name="artist" value="<?php echo $artwork['artist']; ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" name="category" value="<?php echo $artwork['category']; ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price (INR):</label>
            <input type="number" name="price" value="<?php echo $artwork['price']; ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image">
            <p>Current Image: <img src="<?php echo $artwork['image_url']; ?>" width="50"></p>
        </div>
        <button type="submit" class="btn-submit">Update Artwork</button>
    </form>
</div>

</body>
</html>
