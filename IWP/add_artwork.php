<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Artwork</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .form-container h2 {
            color: teal;
            font-family: cursive;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: teal;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #006d5b;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Artwork</h2>
    <form action="add_artwork.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Artwork Title:</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label for="artist">Artist:</label>
            <input type="text" name="artist" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" name="category" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" required>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" required>
        </div>
        <button type="submit">Add Artwork</button>
    </form>
</div>

</body>
</html>
