<?php
    function getUserById($table, $Id) {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM $table WHERE email = :email");
            $stmt->bindParam(':email', $Id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() instead of fetchAll() to get a single row
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    function getAll($table) {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM $table");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    function getDocumentPath($Id) {
        global $conn, $path, $document;
        try {
            $stmt = $conn->prepare("SELECT * FROM documents WHERE id = :id");
            $stmt->bindParam(':id', $Id, PDO::PARAM_STR);
            $stmt->execute();

            $document = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() instead of fetchAll() to get a single row
            return $path = $document['document_path'];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    function approveUser($userId) {
        global $conn;
    
        // Validate the user ID
        if (!is_numeric($userId)) {
            throw new InvalidArgumentException("User ID must be a numeric value.");
        }
    
        // Prepare the SQL statement
        
        $sql = "UPDATE users SET login_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            error_log("Failed to prepare statement: " . implode(" ", $conn->errorInfo()));
            return false;
        }
    
        // Bind the user ID parameter
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    
        // Execute the statement
        if ($stmt->execute()) {
            return true; // User approved successfully
        } else {
            error_log("Error approving user: " . implode(" ", $stmt->errorInfo()));
            return false; // Failed to approve user
        }
    }
    
    

