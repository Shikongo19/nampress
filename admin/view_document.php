<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$document_id = $_GET['id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'your_database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch document path
$sql = "SELECT document_path FROM documents WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $document_id);
$stmt->execute();
$stmt->bind_result($document_path);
$stmt->fetch();
$stmt->close();

if ($document_path) {
    // Display the document (e.g., PDF, image, etc.)
    echo "<embed src='$document_path' width='100%' height='600px' type='application/pdf'>";
} else {
    echo "Document not found.";
}

$conn->close();
?>