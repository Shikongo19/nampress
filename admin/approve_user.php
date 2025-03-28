<?php
    require_once __DIR__ . '/../includes/functions.php'; // Include the shared functions file
    require_once __DIR__ . '/../db/conn.php'; // Include the database connection file

    session_start();
    if (!isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'admin' ) {
        header('Location: ../login.php');
        exit();
    }

    // Approve the user
    if (isset($_GET['id'])) {
        $userId = intval($_GET['id']); // Get the user ID from the query string
    
        if (approveUser($userId)) {
            echo "User approved successfully!";
            header('Location: index.php'); // Redirect back to the admin dashboard
            exit();
        } else {
            echo "Failed to approve user.";
        }
    } else {
        echo "No user ID provided.";
    }

    header('Location: index.php'); // Redirect back to admin portal
?>