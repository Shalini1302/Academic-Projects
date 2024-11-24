<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root"; // Use your database username
$password = ""; // Use your database password
$dbname = "artwork"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User input (from the login form)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the user exists in the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verify password
if ($user && password_verify($password, $user['password'])) {
    // Password is correct, start a session
    $_SESSION['username'] = $username;
    $_SESSION['session_id'] = session_id();  // Store the PHP session ID in a session variable

    // Insert session information into the sessions table
    $sql = "INSERT INTO sessions (session_id, username) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $_SESSION['session_id'], $username);
    $stmt->execute();

    // Redirect to a user dashboard or homepage
    header("Location: dashboard.php");
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
