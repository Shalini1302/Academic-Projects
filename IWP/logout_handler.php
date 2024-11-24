<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['error' => 'No active session']);
    exit;
}

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artwork";

// Get session details
$session_id = session_id();

// Set timezone to Indian Standard Time
date_default_timezone_set('Asia/Kolkata');
$logout_time = date('Y-m-d H:i:s');
$email = $_SESSION['email'];

try {
    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check database connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
    
    // Prepare the update statement
    $sql = "UPDATE sessions SET logout_time = ? WHERE session_id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Statement preparation failed: ' . $conn->error);
    }
    
    // Bind parameters to the SQL statement
    $stmt->bind_param("sss", $logout_time, $session_id, $email);
    
    // Execute the statement
    if (!$stmt->execute()) {
        throw new Exception('Logout update failed: ' . $stmt->error);
    }
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
    // Destroy the session
    session_unset();
    session_destroy();
    
    // Clear session cookies
    setcookie("username", "", time() - 3600, "/", "", true, true);
    setcookie("email", "", time() - 3600, "/", "", true, true);
    
    // Send success response
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Log the error message for debugging
    error_log($e->getMessage());
    
    // Send a general error response
    echo json_encode(['error' => 'Logout failed: ' . $e->getMessage()]);
} finally {
    exit;
}
?>
