<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "artwork"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debug statement to check connection
echo "Connected successfully<br>";

// Fetch all sessions
$sql = "SELECT * FROM sessions";
$result = $conn->query($sql);

// Debug statement to check query execution
if (!$result) {
    die("Query failed: " . $conn->error);
} else {
    echo "Query executed successfully<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Session ID</th>
                    <th>Login Time</th>
                    <th>Last Visit</th>
                    <th>Visit Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['session_id'] . "</td>";
                        echo "<td>" . $row['login_time'] . "</td>";
                        echo "<td>" . $row['last_visit'] . "</td>";
                        echo "<td>" . $row['visit_count'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No active sessions</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
