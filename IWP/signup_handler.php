<?php
// Disable error reporting for production
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/your/logs/php-error.log');

ob_start();
session_start();
header('Content-Type: application/json');

date_default_timezone_set("Asia/Kolkata");

// Function to send JSON response
function sendJsonResponse($data) {
    ob_clean();
    echo json_encode($data);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artwork";

// Database connection
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        error_log("Database connection error: " . $conn->connect_error);
        sendJsonResponse(['error' => 'Database connection failed']);
    }
} catch (Exception $e) {
    sendJsonResponse(['error' => 'Database connection failed']);
}

$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
$password = trim($_POST['password']);
$dob = trim($_POST['dob']);
$profile_type = trim($_POST['profile_type']);

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(['error' => 'Invalid email address']);
}

// Validate profile type
if (!in_array($profile_type, ['artist', 'member'])) {
    sendJsonResponse(['error' => 'Invalid profile type']);
}

// Validate password
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    sendJsonResponse(['error' => 'Password must have at least 8 characters, including uppercase, lowercase, and a number']);
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if email exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        sendJsonResponse(['error' => 'Account already exists with this email']);
    }

    // Check if username exists
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        sendJsonResponse(['error' => 'Username already exists']);
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (email, username, password, dob, profile_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $username, $hashed_password, $dob, $profile_type);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        $_SESSION['profile_type'] = $profile_type;

        sendJsonResponse([
            'success' => true,
            'username' => $username,
            'profile_type' => $profile_type
        ]);
    } else {
        sendJsonResponse(['error' => 'Failed to create account']);
    }
} catch (Exception $e) {
    error_log("Signup error: " . $e->getMessage());
    sendJsonResponse(['error' => 'An error occurred during signup']);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>
