<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Artwork Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
            font-family: cursive;
        }

        .artwork-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .artwork-table th, .artwork-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .artwork-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .artwork-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .artwork-table tr:hover {
            background-color: #ddd;
        }

        .delete-btn, .update-btn {
            padding: 8px 12px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .update-btn {
            background-color: #3498db;
        }

        .update-btn:hover {
            background-color: #2980b9;
        }

        .add-btn {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        .add-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

    <h2>Artwork Management</h2>
    
    <!-- Button to add new artwork -->
    <a href="add_artwork.php" class="add-btn">Add New Artwork</a>

    <table class="artwork-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Artist</th>
            <th>Category</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>

        <?php
        $conn = new mysqli('localhost', 'root', '', 'artwork');
        $result = $conn->query("SELECT * FROM artworks");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['title']."</td>
                <td>".$row['artist']."</td>
                <td>".$row['category']."</td>
                <td>INR ".$row['price']."</td>
                <td><img src='".$row['image']."' width='50' height='50'></td>
                <td>
                    <form action='update_artwork.php' method='POST' style='margin:0;'>
                        <input type='hidden' name='artwork_id' value='".$row['id']."'>
                        <button class='update-btn' type='submit'>Update</button>
                    </form>
                    <form action='delete_artwork.php' method='POST' style='margin:0;'>
                        <input type='hidden' name='artwork_id' value='".$row['id']."'>
                        <button class='delete-btn' type='submit'>Delete</button>
                    </form>
                </td>
            </tr>";
        }
        $conn->close();
        ?>
    </table>

</body>
</html>
