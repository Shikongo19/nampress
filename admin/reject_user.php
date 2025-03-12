<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_GET['id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Reject the user (delete or mark as rejected)
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);

if ($stmt->execute()) {
    echo "User rejected successfully.";
} else {
    echo "Error rejecting user: " . $conn->error;
}

$stmt->close();
$conn->close();

header('Location: index.php'); // Redirect back to admin portal
?>