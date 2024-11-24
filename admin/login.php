<?php
session_start();
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "artwork"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Prevent SQL injection
    $input_username = $conn->real_escape_string($input_username);
    $input_password = $conn->real_escape_string($input_password);

    // Query to fetch the user from the database
    $sql = "SELECT * FROM users WHERE username = '$input_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found
        $row = $result->fetch_assoc();

        // Verify the password (assuming passwords are hashed in the database)
        if (password_verify($input_password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id']; // Optional, store user ID

            // Record session details
            $session_id = session_id();
            $username = $row['username'];
            $current_time = date('Y-m-d H:i:s');
            $sql = "INSERT INTO sessions (session_id, username, login_time, last_visit, visit_count) 
                    VALUES ('$session_id', '$username', '$current_time', '$current_time', 1) 
                    ON DUPLICATE KEY UPDATE last_visit='$current_time', visit_count=visit_count+1";
            $conn->query($sql);

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $error = "Invalid username or password.";
        }
    } else {
        // User not found
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Log In</h2>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Log In</button>
        </form>

        <p>New to ARTSoul? <a href="signup.html">Sign Up</a></p>
    </div>
</body>
</html>
