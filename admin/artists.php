<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artwork";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all artists
$sql = "SELECT * FROM artists";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .artist-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .artist-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            padding: 15px;
            text-align: center;
        }
        .artist-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .artist-card h3 {
            margin: 15px 0 10px;
            font-size: 20px;
            color: teal;
        }
        .artist-card p {
            color: #333;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center; color: teal;">Our Artists</h1>
    <div class="artist-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="artist-card">
                    <?php if (!empty($row['profile_picture'])): ?>
                        <img src="uploads/<?= $row['profile_picture'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <?php else: ?>
                        <img src="default-profile.png" alt="Default Profile">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['bio']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No artists found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
