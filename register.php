<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'nampress');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = $_POST['user_type']; // Individual or Business
    $upload_dir = 'uploads/'; // Directory for uploaded documents

    // Insert user into the database
    $sql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $username, $email, $password, $user_type);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id; // Get the ID of the newly inserted user
        $stmt->close();

        // Handle document uploads
        if ($user_type == 'individual') {
            $document_types = ['id', 'residence'];
        } elseif ($user_type == 'business') {
            $document_types = ['business'];
        }

        foreach ($document_types as $doc_type) {
            if ($_FILES[$doc_type]['error'] == UPLOAD_ERR_OK) {
                $file_name = basename($_FILES[$doc_type]['name']);
                $file_path = $upload_dir . $file_name;

                // Create the uploads directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($_FILES[$doc_type]['tmp_name'], $file_path)) {
                    // Save document details in the database
                    $sql = "INSERT INTO documents (user_id, document_path, document_type) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iss', $user_id, $file_path, $doc_type);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "Error moving uploaded file.";
                }
            } else {
                echo "Error uploading document: " . $_FILES[$doc_type]['error'];
            }
        }

        echo "Registration successful! Please wait for admin approval.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Register</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="individual">Individual Seller</option>
            <option value="business">Business Seller</option>
        </select>
        <br>

        <!-- Document upload fields -->
        <div id="individual_docs">
            <label for="id">ID Document:</label>
            <input type="file" id="id" name="id" accept=".pdf,.jpg,.png">
            <br>
            <label for="residence">Proof of Residence:</label>
            <input type="file" id="residence" name="residence" accept=".pdf,.jpg,.png">
            <br>
        </div>

        <div id="business_docs" style="display: none;">
            <label for="business">Business Document:</label>
            <input type="file" id="business" name="business" accept=".pdf,.jpg,.png">
            <br>
        </div>

        <button type="submit">Register</button>
    </form>

    <script>
        // Show/hide document fields based on user type
        document.getElementById('user_type').addEventListener('change', function () {
            var userType = this.value;
            var individualDocs = document.getElementById('individual_docs');
            var businessDocs = document.getElementById('business_docs');

            if (userType == 'individual') {
                individualDocs.style.display = 'block';
                businessDocs.style.display = 'none';
            } else if (userType == 'business') {
                individualDocs.style.display = 'none';
                businessDocs.style.display = 'block';
            }
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>