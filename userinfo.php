<?php
include 'header.php';
include 'auth.php';
include 'database.php'; // Add this line to include database connection

// Require login to access this page
requireLogin();

// Get current user information
// Use the connection that was already established in database.php
$current_username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE username = ?");
$stmt->bind_param("s", $current_username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
$updateMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_email = htmlspecialchars($_POST["email"]);
    
    // Check if password should be updated
    if (!empty($_POST["new_password"]) && !empty($_POST["current_password"])) {
        // Verify current password
        $verify_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $verify_stmt->bind_param("i", $user_id);
        $verify_stmt->execute();
        $verify_result = $verify_stmt->get_result();
        $user_data = $verify_result->fetch_assoc();
        
        if (password_verify($_POST["current_password"], $user_data['password'])) {
            // Current password is correct, update password
            $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $new_email, $new_password, $user_id);
            
            if ($update_stmt->execute()) {
                $updateMessage = '<div class="success-message">Profile updated successfully!</div>';
            } else {
                $updateMessage = '<div class="error-message">Error updating profile: ' . $conn->error . '</div>';
            }
        } else {
            $updateMessage = '<div class="error-message">Current password is incorrect</div>';
        }
    } else {
        // Just update email
        $update_stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_email, $user_id);
        
        if ($update_stmt->execute()) {
            $updateMessage = '<div class="success-message">Email updated successfully!</div>';
        } else {
            $updateMessage = '<div class="error-message">Error updating email: ' . $conn->error . '</div>';
        }
    }
    
    // Refresh user data after update
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Fetch booking history
$bookings = [];
$booking_stmt = $conn->prepare("SELECT e.name as event_name, b.event_date, b.num_tickets, b.booking_date 
                               FROM bookings b 
                               JOIN events e ON b.event_id = e.id 
                               WHERE b.user_id = ? 
                               ORDER BY b.booking_date DESC");

// Check if the bookings table and events table exist
$bookingsExist = $conn->query("SHOW TABLES LIKE 'bookings'")->num_rows > 0;
$eventsExist = $conn->query("SHOW TABLES LIKE 'events'")->num_rows > 0;

if ($bookingsExist && $eventsExist) {
    $booking_stmt->bind_param("i", $user_id);
    $booking_stmt->execute();
    $booking_result = $booking_stmt->get_result();
    
    while ($row = $booking_result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <style>
        .account-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .account-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .account-section {
            margin-bottom: 30px;
        }
        
        .account-section h3 {
            margin-bottom: 15px;
            color: #4CAF50;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-update {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .booking-history {
            width: 100%;
            border-collapse: collapse;
        }
        
        .booking-history th, .booking-history td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .booking-history th {
            background-color: #f2f2f2;
        }
        
        .booking-history tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="account-container">
        <div class="account-header">
            <h2>My Account</h2>
            <p>Welcome, <?php echo htmlspecialchars($current_username); ?>!</p>
        </div>
        
        <?php echo $updateMessage; ?>
        
        <div class="account-section">
            <h3>Profile Information</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    <small>(Username cannot be changed)</small>
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Member Since:</label>
                    <input type="text" value="<?php echo date('F j, Y', strtotime($user['created_at'])); ?>" readonly>
                </div>
                
                <h4>Change Password (Optional)</h4>
                
                <div class="form-group">
                    <label>Current Password:</label>
                    <input type="password" name="current_password">
                </div>
                
                <div class="form-group">
                    <label>New Password:</label>
                    <input type="password" name="new_password">
                </div>
                
                <button type="submit" name="update_profile" class="btn-update">Update Profile</button>
            </form>
        </div>
        
        <div class="account-section">
            <h3>Your Booking History</h3>
            
            <?php if ($bookingsExist && $eventsExist && count($bookings) > 0): ?>
                <table class="booking-history">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Tickets</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['event_name']); ?></td>
                            <td><?php echo date('F j, Y', strtotime($booking['event_date'])); ?></td>
                            <td><?php echo $booking['num_tickets']; ?></td>
                            <td><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>You haven't booked any events yet.</p>
                    <p><a href="feature.php">Browse Events</a> to make your first booking!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
if (isset($booking_stmt)) {
    $booking_stmt->close();
}
$conn->close();
include 'footer.php';
?>