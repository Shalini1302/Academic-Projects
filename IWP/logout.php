<?php
session_start();
include 'db_connect.php'; // Include database connection

// Delete session data from the database
if (isset($_SESSION['session_id'])) {
    $session_id = $_SESSION['session_id'];
    $delete_query = "DELETE FROM sessions WHERE session_id = '$session_id'";
    mysqli_query($conn, $delete_query);
}

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
?>
