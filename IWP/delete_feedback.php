<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback_id = $_POST['feedback_id'];
    
    $conn = new mysqli('localhost', 'root', '', 'artwork');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM feedback WHERE feedback_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);

    if ($stmt->execute()) {
        echo "Feedback deleted successfully!";
    } else {
        echo "Error deleting feedback: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    
    // Redirect back to the admin page
    header("Location: a.php");
    exit;
}
?>
