<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Only define functions if they don't already exist
if (!function_exists('isLoggedIn')) {
    // Check if user is logged in
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('requireLogin')) {
    // Redirect if user is not logged in
    function requireLogin() {
        if (!isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }
}

if (!function_exists('getCurrentUserId')) {
    // Get current user ID
    function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}

if (!function_exists('getCurrentUsername')) {
    // Get current username
    function getCurrentUsername() {
        return $_SESSION['username'] ?? null;
    }
}

if (!function_exists('isAdmin')) {
    // Check if current user is an admin
    function isAdmin() {
        if (!isLoggedIn()) {
            return false;
        }
        
        // Create database connection inside the function to ensure it exists
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "user_registration";
        
        // Create a new connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            // If connection fails, just return false - not an admin
            return false;
        }
        
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $is_admin = false;
        if ($row = $result->fetch_assoc()) {
            $is_admin = (bool)$row['is_admin'];
        }
        
        // Close the connection
        $stmt->close();
        $conn->close();
        
        return $is_admin;
    }
}
?>