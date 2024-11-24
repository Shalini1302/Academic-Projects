<?php
include 'db_connection.php'; // Include the database connection

// Fetch all sales from the database
$query = "SELECT * FROM sales ORDER BY price DESC";
$result = mysqli_query($conn, $query);

// Function to calculate discount
function calculateDiscount($price, $discountPercent) {
    return $price - ($price * $discountPercent / 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Display - Online Artworks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        #sales-title {
            text-align: center;
            font-size: 3em;
            font-family: 'Times New Roman';
            cursor: pointer;
            color: black;
        }
        .sale {
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            position: relative; /* For positioning the discount badge */
        }
        .sale img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 5px;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: white;
            padding: 5px 10px;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
        }
        .details {
            display: none;
        }
        .view-details, .add-to-cart {
            margin-top: 10px;
            cursor: pointer;
        }
        .sale-title {
            font-size: 24px;
            color: black;
            transition: color 0.3s ease-in-out;
        }
        .price {
            font-size: 18px;
            color: green;
        }
        .original-price {
            text-decoration: line-through;
            color: red;
            font-size: 16px;
        }
        .offer-price {
            font-size: 18px;
            color: green;
            font-weight: bold;
        }
        .btn {
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- Sales Display Title -->
    <div class="jumbotron text-center" style="background-image: url('http://localhost/admin/images/sales.gif'); background-size: cover; background-position: center; color: white; padding: 50px 0; height:100vh;">
    </div>

    <div class="container">
        <div class="row">
            <?php while ($sale = mysqli_fetch_assoc($result)): 
                $discountPercent = rand(10, 20); // Generate a random discount between 10-20%
                $discountedPrice = calculateDiscount($sale['price'], $discountPercent);
            ?>
                <div class="col-md-4">
                    <div class="sale">
                        <div class="discount-badge"><?php echo $discountPercent; ?>% OFF</div>
                        <img src="<?php echo $sale['image_url']; ?>" alt="<?php echo $sale['title']; ?>" class="sale-img">
                        <h4 class="sale-title"><?php echo $sale['title']; ?><br> -by <?php echo $sale['artist_name']; ?></h4>
                        <p class="price">
                            <span class="original-price">INR <?php echo number_format($sale['price'], 2); ?></span>
                            <span class="offer-price">INR <?php echo number_format($discountedPrice, 2); ?></span>
                        </p>
                        <button class="btn btn-primary view-details" onclick="toggleDetails(<?php echo $sale['id']; ?>)">View Details</button>
                        <button class="btn btn-success add-to-cart" onclick="addToCart(<?php echo $sale['id']; ?>)">Add to Cart</button>
                        <div id="details-<?php echo $sale['id']; ?>" class="details">
                            <p><strong>Description:</strong> <?php echo $sale['description']; ?></p>
                            <p><strong>Original Price:</strong> <span class="original-price">INR <?php echo number_format($sale['price'], 2); ?></span></p>
                            <p><strong>Discounted Price:</strong> <span class="offer-price">INR <?php echo number_format($discountedPrice, 2); ?></span></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Toggle the visibility of the sale's details
        function toggleDetails(saleId) {
            var details = document.getElementById('details-' + saleId);
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        }

 function addToCart(saleId) {
    $.ajax({
        url: 'add_to_cart.php',
        type: 'POST',
        data: { sale_id: saleId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                $(".cart-count").text(response.cart_count);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", xhr.responseText);
            alert("Error adding to cart");
        }
    });
}

</script>
</body>
</html>
