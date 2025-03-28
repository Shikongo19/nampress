<?php
require_once __DIR__ . '/../includes/functions.php'; // Include the shared functions file
require_once __DIR__ . '/../db/conn.php'; // Include the database connection 

session_start();
if (!isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'admin' ) {
    header('Location: ../login.php');
    exit();
}

$document_id = $_GET['id'];
$path = getDocumentPath($document_id);

if ($path) {
    // Display the document (e.g., PDF, image, etc.)
    echo "<embed src='http://localhost/nampress/$path' width='100%' height='600px' type='application/pdf'>";
} else {
    echo "Document not found.";
}
?>