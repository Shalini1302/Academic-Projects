<?php
// Start the session
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'artwork');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch session logs from the database
$query = "SELECT * FROM sessions"; // Query to get session logs
$result = $conn->query($query);

// Check if any results are returned
if ($result->num_rows > 0) {
    $sessions = $result->fetch_all(MYSQLI_ASSOC);  // Fetch all results as an associative array
} else {
    $sessions = [];  // If no results, return an empty array
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
            font-family: cursive;
        }

        .session-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .session-table th, .session-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .session-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .session-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .session-table tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Session Logs</h2>

    <!-- Display session data in a table -->
    <table class="session-table">
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Email</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Last Activity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the session logs and display them
            foreach ($sessions as $session) {
                echo "<tr>
                        <td>" . htmlspecialchars($session['session_id']) . "</td>
                        <td>" . htmlspecialchars($session['email']) . "</td>
                        <td>" . htmlspecialchars($session['login_time']) . "</td>
                        <td>" . htmlspecialchars($session['logout_time']) . "</td>
                        <td>" . htmlspecialchars($session['last_activity']) . "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
