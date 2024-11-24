<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artwork";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a Feedback class to hold feedback details
class Feedback {
    public $name;
    public $email;
    public $phone;
    public $feedback_type;
    public $description;

    function __construct($name, $email, $phone, $feedback_type, $description) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->feedback_type = $feedback_type;
        $this->description = $description;
    }

    function displayFeedback() {
        return "Name: $this->name, Email: $this->email, Phone: $this->phone, Feedback Type: $this->feedback_type, Description: $this->description";
    }
}

// Processing the form when submitted
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';  // Check if phone is set
    $feedback_type = $_POST['feedback_type'];
    $description = $_POST['description'];

    // Validate feedback description length
    if (strlen($description) < 10) {
        echo '<script>alert("Feedback description should be at least 10 characters long.");</script>';
    } else {
        // Create feedback object
        $feedback = new Feedback($name, $email, $phone, $feedback_type, $description);

        // Insert feedback into the database
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, phone, feedback_type, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $feedback_type, $description);  // Bind phone parameter

        if ($stmt->execute()) {
            echo '<script>alert("Feedback Successfully Submitted!"); window.location = "index.html";</script>';
        } else {
            echo '<script>alert("Feedback Submission Failed!");</script>';
        }

        $stmt->close();
    }
}

$conn->close();
?>
