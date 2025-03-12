<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete the product
$sql = "DELETE FROM products WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $product_id, $user_id);

if ($stmt->execute()) {
    echo "Product deleted successfully.";
} else {
    echo "Error deleting product: " . $conn->error;
}

$stmt->close();
$conn->close();

header('Location: index.php'); // Redirect back to client portal
?>