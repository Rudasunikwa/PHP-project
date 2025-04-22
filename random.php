<?php 
include 'header.php';
include 'auth.php';

// Require login to access this page
requireLogin();

// Check if we have a booking confirmation
$has_confirmation = isset($_SESSION['booking_confirmation']);
$booking_info = $has_confirmation ? $_SESSION['booking_confirmation'] : null;

// Generate a ticket number
$ticket_number = rand(10000, 99999);

// If this is a new confirmed booking, add the ticket number to the session data
if ($has_confirmation && !isset($booking_info['ticket_number'])) {
    $_SESSION['booking_confirmation']['ticket_number'] = $ticket_number;
    $booking_info = $_SESSION['booking_confirmation'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking Confirmation</title>
    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 30px auto;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .confirmation-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ticket-number {
            font-size: 28px;
            font-weight: bold;
            color: #fff;
            background: #4CAF50;
            padding: 20px;
            display: inline-block;
            border-radius: 10px;
            margin: 20px 0;
        }
        .booking-details {
            margin: 20px 0;
            text-align: left;
            font-size: 16px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dotted #ddd;
        }
        .success-icon {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .actions {
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }
        .btn-secondary {
            background-color: #555;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background-color: white;
            }
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <?php if ($has_confirmation): ?>
            <div class="confirmation-box">
                <div class="success-icon">✓</div>
                <h1>Booking Confirmed!</h1>
                <p>Thank you for your booking. Your reservation is now confirmed.</p>
                
                <div class="ticket-info">
                    <p>Your Ticket Number:</p>
                    <div class="ticket-number"><?php echo $booking_info['ticket_number']; ?></div>
                    <p>Please save this number for your records.</p>
                </div>
                
                <div class="booking-details">
                    <h3>Booking Details</h3>
                    <div class="detail-row">
                        <span>Event:</span>
                        <span><?php echo htmlspecialchars($booking_info['event_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Booking ID:</span>
                        <span>#<?php echo $booking_info['booking_id']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Number of Tickets:</span>
                        <span><?php echo $booking_info['num_tickets']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Total Paid:</span>
                        <span>$<?php echo number_format($booking_info['total_amount'], 2); ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Payment Method:</span>
                        <span><?php echo ucfirst(str_replace('_', ' ', $booking_info['payment_method'])); ?></span>
                    </div>
                </div>
                
                <div class="actions no-print">
                    <button onclick="window.print()" class="btn">Print Ticket</button>
                    <a href="userinfo.php" class="btn">View My Bookings</a>
                    <a href="feature.php" class="btn btn-secondary">Browse More Events</a>
                </div>
            </div>
        <?php else: ?>
            <div class="confirmation-box">
                <h2>No Active Booking</h2>
                <p>It seems you haven't completed a booking yet.</p>
                <div class="actions">
                    <a href="feature.php" class="btn">Browse Events</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>