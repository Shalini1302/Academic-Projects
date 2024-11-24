<?php
session_start();
include 'db_connection.php';

function signup($username, $password) {
    global $conn;
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if username already exists
    $check_query = "SELECT * FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        return false; // Username already exists
    }
    
    // Insert new user
    $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ss", $username, $hashed_password);
    
    if ($insert_stmt->execute()) {
        // Auto-login after successful signup
        $_SESSION['user_id'] = $insert_stmt->insert_id;
        $_SESSION['username'] = $username;
        
        // Optional: Create a session tracking entry
        $session_token = bin2hex(random_bytes(16));
        $session_query = "INSERT INTO user_sessions (user_id, session_token, created_at) 
                          VALUES (?, ?, NOW())";
        $session_stmt = $conn->prepare($session_query);
        $session_stmt->bind_param("is", $_SESSION['user_id'], $session_token);
        $session_stmt->execute();
        
        return true;
    }
    
    return false;
}

// Usage in signup process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (signup($username, $password)) {
        // Redirect to dashboard or home page
        header("Location: shal home.html");
        exit();
    } else {
        $error = "Signup failed. Username might already exist.";
    }
}
?>