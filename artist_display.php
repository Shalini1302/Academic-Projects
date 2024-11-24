<?php
include 'db_connection.php'; // Include the database connection

// Fetch all artists from the database
$query = "SELECT * FROM artists ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Display - Online Artworks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .jumbotron {
            background-image: url('https://images.unsplash.com/photo-1533053271127-8730d5ff14e4');
            background-size: cover;
            background-position: center;
            color: violet;
            background-color:pink;
            padding: 80px 0;
        }
        #artist-title {
            text-align: center;
            font-size: 3em;
            font-family: 'Lucida Handwriting';
            cursor: pointer;
        }
        .artist {
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
        }
        .artist img {
            width: 100%; /* or a specific width, e.g., 200px */
            height: 300px; /* Fixed height */
            object-fit: cover; /* Ensures the image maintains its aspect ratio while filling the box */
            border-radius: 5px; /* Optional: adds rounded corners */
        }
        .bio {
            display: none;
        }
        .view-bio {
            margin-top: 10px;
            cursor: pointer;
        }
        .artist-name {
            font-size: 24px;
            color: black;
            transition: color 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Artist Display Title -->
    <div class="jumbotron text-center">
        <h1 id="artist-title">Our Featured Artists</h1>
    </div>

    <div class="container">
        <div class="row">
            <?php while ($artist = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="artist">
                        <img src="<?php echo $artist['profile_picture']; ?>" alt="<?php echo $artist['name']; ?>" class="artist-img">
                        <h4 class="artist-name"><?php echo $artist['name']; ?></h4>
                        <button class="btn btn-primary view-bio" onclick="toggleBio(<?php echo $artist['id']; ?>)">View Bio</button>
                        <div id="bio-<?php echo $artist['id']; ?>" class="bio">
                            <p><strong>Biography:</strong> <?php echo $artist['bio']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        // Toggle the visibility of the artist's bio
        function toggleBio(artistId) {
            var bio = document.getElementById('bio-' + artistId);
            if (bio.style.display === 'none' || bio.style.display === '') {
                bio.style.display = 'block';
            } else {
                bio.style.display = 'none';
            }
        }
    </script>
</body>
</html>
