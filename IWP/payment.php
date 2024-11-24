<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission
    $amount = $_POST['amount'];
    $paymentMethod = $_POST['paymentMethod'];
    $cardNumber = $_POST['cardNumber'] ?? null;
    $expiryDate = $_POST['expiryDate'] ?? null;
    $cvv = $_POST['cvv'] ?? null;
    $upiId = $_POST['upiId'] ?? null;

    // Define the Payment class
    class Payment {
        public $amount;
        public $paymentMethod;
        public $cardNumber;
        public $expiryDate;
        public $cvv;
        public $upiId;

        public function __construct($amount, $paymentMethod, $cardNumber, $expiryDate, $cvv, $upiId) {
            $this->amount = $amount;
            $this->paymentMethod = $paymentMethod;
            $this->cardNumber = $cardNumber;
            $this->expiryDate = $expiryDate;
            $this->cvv = $cvv;
            $this->upiId = $upiId;
        }

        // Validate payment details using regular expressions
        public function validatePaymentDetails() {
            if ($this->paymentMethod === "Credit Card" || $this->paymentMethod === "Debit Card") {
                return preg_match("/^[0-9]{16}$/", $this->cardNumber) &&
                       preg_match("/^(0[1-9]|1[0-2])\/([0-9]{2})$/", $this->expiryDate) &&
                       preg_match("/^[0-9]{3,4}$/", $this->cvv);
            } elseif ($this->paymentMethod === "upi" || $this->paymentMethod === "GPay") {
                return preg_match("/^[\w.-]+@(oksbi|okaxis|okhdfc|okicici|okpaytm)$/", $this->upiId);
            } else {
                return false;
            }
        }

        // Simulate payment processing
        public function processPayment() {
            if ($this->validatePaymentDetails()) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Create a Payment object and process the payment
    $payment = new Payment($amount, $paymentMethod, $cardNumber, $expiryDate, $cvv, $upiId);
    $paymentSuccess = $payment->processPayment();

    if ($paymentSuccess) {
        // Redirect to success page
        header("Location: payment_success.php");
        exit();
    } else {
        $result = "Invalid payment details. Please check your information and try again.";
    }
} else {
    // Retrieve the amount from the GET request
    $amount = $_GET['amount'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #343a40;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result-message {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            color: red;
        }
    </style>
    <script>
        function togglePaymentFields() {
            var paymentMethod = document.getElementById("paymentMethod").value;
            var cardDetails = document.getElementById("card-details");
            var upiDetails = document.getElementById("upi-details");

            if (paymentMethod === "Credit Card" || paymentMethod === "Debit Card") {
                cardDetails.style.display = "block";
                upiDetails.style.display = "none";
            } else {
                cardDetails.style.display = "none";
                upiDetails.style.display = "block";
            }
        }

        window.onload = function() {
            togglePaymentFields(); // Initialize the visibility on page load
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Artwork Payment</h2>

        <!-- Display result -->
        <?php if (isset($result)) { echo "<p class='result-message'>$result</p>"; } ?>

        <!-- Payment Form -->
        <form action="payment_success.php" method="post">
            <label for="amount">Amount (INR):</label>
            <input type="text" id="amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" readonly>

            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod" name="paymentMethod" onchange="togglePaymentFields()" required>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="upi">UPI</option>
                <option value="GPay">GPay</option>
            </select>

            <div id="card-details">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber">

                <label for="expiryDate">Expiry Date (MM/YY):</label>
                <input type="text" id="expiryDate" name="expiryDate">

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv">
            </div>

            <div id="upi-details">
                <label for="upiId">UPI ID:</label>
                <input type="text" id="upiId" name="upiId">
            </div>

            <input type="submit" value="Submit Payment">
        </form>
    </div>
</body>
</html>
