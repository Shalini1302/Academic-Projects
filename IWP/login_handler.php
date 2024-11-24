<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

date_default_timezone_set("Asia/Kolkata");

function sendJsonResponse($data) {
    echo json_encode($data);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artwork";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        sendJsonResponse(['error' => 'Database connection failed: ' . $conn->connect_error]);
    }
    $conn->query("SET time_zone = '+05:30'");
} catch (Exception $e) {
    sendJsonResponse(['error' => 'Database connection failed']);
}

if (!isset($_POST['email-username']) || !isset($_POST['password'])) {
    sendJsonResponse(['error' => 'Please provide all required fields']);
}

$email_username = trim($_POST['email-username']);
$password = trim($_POST['password']);

if (empty($email_username) || empty($password)) {
    sendJsonResponse(['error' => 'Please provide all required fields']);
}

try {
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_username, $email_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($stmt->error) {
        sendJsonResponse(['error' => 'Query error: ' . $stmt->error]);
    }

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Regenerate session ID to avoid session fixation

            // Save session data in $_SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;
            $_SESSION['profile_type'] = $user['profile_type']; // In $_SESSION for user info
            $_SESSION['last_activity'] = time(); // In $_SESSION for user activity tracking
            $_SESSION['user_icon'] = $user['user_icon']; // In $_SESSION for user icon

            // Insert session details into the sessions table
            $session_id = session_id();
            $login_time = date('Y-m-d H:i:s');
            $email = $user['email'];

            $sql = "INSERT INTO sessions (session_id, email, login_time) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $session_id, $email, $login_time);
            $stmt->execute();

            if ($stmt->error) {
                sendJsonResponse(['error' => 'Error saving session details']);
            }

            // Optionally, set cookies for auto-login (if required)
            setcookie('user_id', $user['id'], time() + 3600 * 24 * 7, "/");
            setcookie('username', $user['username'], time() + 3600 * 24 * 7, "/");
            setcookie('user_icon', $user['user_icon'], time() + 3600 * 24 * 7, "/");

            sendJsonResponse([
                'success' => true,
                'username' => $user['username'],
                'user_icon' => $user['user_icon']
            ]);
        } else {
            sendJsonResponse(['error' => 'Invalid email/username or password']);
        }
    } else {
        sendJsonResponse(['error' => 'Invalid email/username or password']);
    }

} catch (Exception $e) {
    sendJsonResponse(['error' => 'An error occurred during login']);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>