<?php
$conn = new mysqli("localhost", "root", "", "artwork");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$sql = "SELECT COUNT(DISTINCT email) AS daily_visits FROM sessions WHERE DATE(login_time) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo "Number of users visited today: " . $data['daily_visits'];

$stmt->close();
$conn->close();
?>
