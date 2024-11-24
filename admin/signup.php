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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $profileType = $_POST['profile-type'];

    // Prevent SQL injection
    $email = $conn->real_escape_string($email);
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $dob = $conn->real_escape_string($dob);
    $profileType = $conn->real_escape_string($profileType);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $sql = "INSERT INTO users (username, password, email, dob, profile_type) VALUES ('$username', '$hashed_password', '$email', '$dob', '$profileType')";

    if ($conn->query($sql) === TRUE) {
        // After successful signup, start the session
        $_SESSION['username'] = $username;  // Store the user's username in the session
        $_SESSION['session_id'] = session_id();  // Store the session ID

        // Record session details
        $session_id = session_id();
        $current_time = date('Y-m-d H:i:s');
        $sql = "INSERT INTO sessions (session_id, username, login_time, last_visit, visit_count) 
                VALUES ('$session_id', '$username', '$current_time', '$current_time', 1)";
        $conn->query($sql);

        header("Location: dashboard.php");  // Redirect to the dashboard after signup
        exit();
    } else {
        // Registration failed
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="signup.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>

            <label for="profile-type">Choose your Profile-Type</label>
            <select id="profile-type" name="profile-type" required>
                <option value="artist">ARTIST</option>
                <option value="member">MEMBER</option>
            </select>

            <button type="submit" id="submitBtn">Sign Up</button>
        </form>
        <p>Already signed up? <a href="login.html">Log In here!</a></p>
    </div>
</body>
</html>
