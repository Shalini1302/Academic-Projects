<?php
class ArtworkBill {
    private $billNumber;
    private $date;
    private $paymentDetails;
    
    public function __construct($paymentData) {
        $this->billNumber = 'BILL-' . date('Ymd') . '-' . rand(1000, 9999);
        $this->date = date('Y-m-d H:i:s');
        $this->paymentDetails = $paymentData;
    }
    
    public function generateBill() {
        return '
        <div class="bill-container">
            <div class="bill-header">
                <h2 style="color:red;font-family:papyrus;">ARTSoul</h2>
                <h3>Purchase Bill</h3>
                <p>Bill No: ' . $this->billNumber . '</p>
                <p>Date: ' . $this->date . '</p>
            </div>
            
            <div class="bill-details">
                <h3>Payment Details:</h3>
                <p>Amount Paid: ₹' . number_format($this->paymentDetails['amount'], 2) . '</p>
                <p>Payment Method: ' . $this->paymentDetails['paymentMethod'] . '</p>
                <p>Transaction ID: ' . $this->billNumber . '</p>
            </div>

            <div class="total-section">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: right"><strong>Subtotal:</strong></td>
                        <td style="text-align: right">₹' . number_format($this->paymentDetails['amount'] * 0.82, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: right"><strong>GST (18%):</strong></td>
                        <td style="text-align: right">₹' . number_format($this->paymentDetails['amount'] * 0.18, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: right"><strong>Total Amount:</strong></td>
                        <td style="text-align: right">₹' . number_format($this->paymentDetails['amount'], 2) . '</td>
                    </tr>
                </table>
            </div>

            <div class="thank-you">
                <p>Thank you for your purchase!</p>
            </div>
        </div>';
    }
}

// Get payment data from POST
$paymentData = [
    'amount' => $_POST['amount'] ?? 0,
    'paymentMethod' => $_POST['paymentMethod'] ?? '',
    'date' => date('Y-m-d H:i:s')
];

// Generate bill
$bill = new ArtworkBill($paymentData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #343a40;
        }
        .success-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            margin-bottom: 20px;
        }
        .success-icon {
            font-size: 72px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 20px;
        }
        .bill-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .bill-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .bill-details {
            margin-bottom: 20px;
        }
        .total-section {
            margin: 20px 0;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .thank-you {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #28a745;
        }
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn-print {
            background-color: #007bff;
            color: white;
        }
        .btn-home {
            background-color: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        @media print {
            .success-container, .action-buttons {
                display: none;
            }
            .bill-container {
                box-shadow: none;
            }
        }
    </style>
    <script>
        // Redirect to home page after 30 seconds
        setTimeout(function() {
            window.location.href = 'shal home.html';
        }, 30000);

        function printBill() {
            window.print();
        }
    </script>
</head>
<body>
    <!-- Success Message -->
    <div class="success-container">
        <div class="success-icon">&#10004;</div>
        <h1 class="success-message">Payment Successful!</h1>
        <p>Your transaction has been completed successfully.</p>
    </div>

    <!-- Bill Section -->
    <?php echo $bill->generateBill(); ?>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn btn-print" onclick="printBill()">Print Bill</button>
        <button class="btn btn-home" onclick="window.location.href='shal home.html'">Back to Home</button>
    </div>
</body>
</html>