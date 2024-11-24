<?php
session_start(); // Start session to handle login checks
include 'db_connection.php'; // Include the database connection

// Fetch all artworks from the database
$query = "SELECT * FROM artworks";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork Display - Online Artworks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        #artwork-title {
            font-size: 3em;
            color: whitesmoke;
            text-align: center;
            font-family: papyrus;
            margin: 20px auto;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }
        .artwork {
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #ccc;
            padding: 15px;
        }
        .artwork img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 5px;
        }
        .details {
            display: none; /* Hide details initially */
        }
        .add-to-cart {
            margin-top: 10px;
            cursor: pointer;
        }
        .art-name {
            font-size: 24px;
            color: black;
            transition: color 0.3s ease-in-out;
        }
        .artist_name {
            font-size: 18px;
            color: black;
            cursor: pointer;
        }
        a{
             float:left;
             font-size:32px; 
             text-decoration:none;
             margin: 10px 10px;  
             color: white;     
        }

        a:hover{
             color: whitesmoke;
             text-decoration:none;
        }

        .navbar{
             background-color:black;
             margin:0 auto;
             padding:10px;
             width:100%;
             height:100%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="http://localhost/iwp/shal%20home.html"><i class="fas fa-palette"></i>ARTSoul</a>
        <h1 id="artwork-title">Artwork Display</h1>
    </div>

    <!-- Artwork Display -->
    <div class="container">
        <div class="row">
            <?php while ($artwork = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="artwork">
                        <img src="<?php echo $artwork['image_url']; ?>" alt="<?php echo $artwork['title']; ?>" class="art-img">
                        <h4 class="art-name"><?php echo $artwork['title']; ?></h4>
                        <p><strong class="artist_name">Artist:</strong> <span class="artist_name"><?php echo $artwork['artist_name']; ?></span></p>
                        <p><strong class="artist_name">Price:</strong> <span class="artist_name"><?php echo $artwork['price']; ?></span></p>
                        <button class="btn btn-primary show-details">View Details</button>
                        <!-- Add to Cart button with updated onclick -->
                        <button class="btn btn-success add-to-cart" onclick="addToCart(<?php echo $artwork['id']; ?>)">Add to Cart</button>
                        <div class="details">
                            <p><strong>Description:</strong> <?php echo $artwork['description']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You need to log in to add items to your cart.</p>
                    <a href="http://localhost/iwp/shal%20home.html" class="btn btn-primary">Go to Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
            // Bounce effect with color change for artwork name
            $(".art-name").hover(function() {
                $(this).animate({ fontSize: "30px", color: "#e74c3c" }, 300).animate({ fontSize: "24px", color: "#000" }, 300);
            });

            // Change color of artist name on click
            $(".artist_name").click(function() {
                $(this).css("color", "#3498db");
            });

            // FadeIn and FadeOut effect on artwork image when hovered
            $(".art-img").hover(function() {
                $(this).fadeOut(500).fadeIn(500);
            });

            // SlideIn and SlideOut effect for "View Details" button
            $(".show-details").click(function() {
                $(this).nextAll(".details").slideToggle(1000); // This controls the description toggle
            });

            // Hide and Show effect for "Add to Cart" button
            $(".add-to-cart").click(function() {
                $(this).hide(300).show(300);
            });
        });

// In artwork display page's JavaScript
function addToCart(artworkId) {
    var loggedIn = <?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>;

    if (!loggedIn) {
        $('#loginModal').modal('show');
    } else {
        $.ajax({
            url: 'add_to_cart.php',  // Double-check this path
            type: 'POST',
            data: { artwork_id: artworkId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Update cart count element if you have one
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error details:", xhr.responseText);
                alert("Error adding to cart");
            }
        });
    }
}
</script>
</body>
</html>
