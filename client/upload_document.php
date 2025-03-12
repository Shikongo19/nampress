<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document_type = $_POST['document_type'];
    $upload_dir = 'uploads/'; // Ensure this directory exists and is writable

    // Handle file upload
    if ($_FILES['document']['error'] == UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['document']['name']);
        $file_path = $upload_dir . $file_name;

        // Create the uploads directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['document']['tmp_name'], $file_path)) {
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'your_database');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Save document details in the database
            $sql = "INSERT INTO documents (user_id, document_path, document_type) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iss', $user_id, $file_path, $document_type);

            if ($stmt->execute()) {
                echo "Document uploaded successfully.";
            } else {
                echo "Error uploading document: " . $conn->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Error moving uploaded file.";
        }
    } else {
        echo "File upload error. Error code: " . $_FILES['document']['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Document</title>
</head>
<body>
    <h1>Upload Document</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="document_type">Document Type:</label>
        <select id="document_type" name="document_type" required>
            <option value="business">Business Document</option>
            <option value="id">ID Document</option>
            <option value="residence">Proof of Residence</option>
        </select>
        <br>
        <label for="document">Document:</label>
        <input type="file" id="document" name="document" required>
        <br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>