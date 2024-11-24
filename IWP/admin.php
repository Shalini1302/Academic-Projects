<?php
session_start();
include 'db_connection.php'; // Include the database connection

// Check if admin is logged in (basic check)
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    echo "Access Denied. Only admin can view this page.";
    exit();
}

// Fetch all session data
$query = "SELECT * FROM sessions ORDER BY login_time DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Session Tracking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Session Tracking - Logged-in Users</h2>
    <table>
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Username</th>
                <th>Login Time</th>
                <th>Visit Count</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['session_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['login_time']; ?></td>
                    <td><?php echo $row['visit_count']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
