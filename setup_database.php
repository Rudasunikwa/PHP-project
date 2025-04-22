<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS user_registration";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("user_registration");

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully or already exists<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Check if admin column exists in users table and update first user if needed
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
$adminColumnExists = ($result->num_rows > 0);

if (!$adminColumnExists) {
    $sql = "ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0";
    if ($conn->query($sql) === TRUE) {
        echo "Admin column added to users table<br>";
        
        // Make the first user an admin if there are any users
        $sql = "UPDATE users SET is_admin = 1 WHERE id = 1";
        $conn->query($sql);
        echo "First user set as admin<br>";
    } else {
        echo "Error adding admin column: " . $conn->error . "<br>";
    }
}

echo "Database setup completed. <a href='login.php'>Go to login page</a>";

$conn->close();
?>