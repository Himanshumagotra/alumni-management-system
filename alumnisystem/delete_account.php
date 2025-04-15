<?php
session_start();
if (!isset($_SESSION['alumni_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumni_id = $_SESSION['alumni_id'];
    
    // Delete the alumni record
    $stmt = $conn->prepare("DELETE FROM alumni WHERE id = ?");
    $stmt->bind_param("i", $alumni_id);
    
    if ($stmt->execute()) {
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login page with success message
        header("Location: login.php?message=account_deleted");
        exit();
    } else {
        header("Location: welcome.php?error=delete_failed");
        exit();
    }
}

// If not POST request, redirect to welcome page
header("Location: welcome.php");
exit();
?> 