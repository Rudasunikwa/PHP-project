<?php 
include 'header.php';
include 'auth.php';
include 'database.php';

// Require login to access this page
requireLogin();

// Initialize variables
$event_id = '';
$num_tickets = 0;
$event_details = null;
$total_amount = 0;

// Check if we have booking data from the form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id']) && isset($_POST['num_tickets'])) {
    $event_id = $_POST['event_id'];
    $num_tickets = $_POST['num_tickets'];
    
    // Get event details
    $stmt = $conn->prepare("SELECT name, event_date, event_time, price FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $event_details = $result->fetch_assoc();
        $total_amount = $event_details['price'] * $num_tickets;
    } else {
        // Event not found - redirect to events page
        header("Location: feature.php");
        exit();
    }
    
    // Store booking data in session for payment processing
    $_SESSION['booking_data'] = [
        'event_id' => $event_id,
        'num_tickets' => $num_tickets,
        'event_date' => $event_details['event_date'],
        'total_amount' => $total_amount
    ];
} else if (isset($_SESSION['booking_data'])) {
    // Retrieve booking data from session if coming back to this page
    $event_id = $_SESSION['booking_data']['event_id'];
    $num_tickets = $_SESSION['booking_data']['num_tickets'];
    $total_amount = $_SESSION['booking_data']['total_amount'];
    
    // Get event details
    $stmt = $conn->prepare("SELECT name, event_date, event_time, price FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $event_details = $result->fetch_assoc();
    }
} else {
    // No booking data - redirect to events page
    header("Location: feature.php");
    exit();
}

// Process payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];
    
    // Insert booking into database
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, event_date, num_tickets, total_amount, payment_status) 
                           VALUES (?, ?, ?, ?, ?, 'completed')");
    $stmt->bind_param("iisid", $user_id, $event_id, $event_details['event_date'], $num_tickets, $total_amount);
    
    if ($stmt->execute()) {
        // Booking successful - redirect to confirmation page
        $booking_id = $conn->insert_id;
        $_SESSION['booking_confirmation'] = [
            'booking_id' => $booking_id,
            'event_name' => $event_details['name'],
            'num_tickets' => $num_tickets,
            'total_amount' => $total_amount,
            'payment_method' => $payment_method
        ];
        
        // Clear booking data from session
        unset($_SESSION['booking_data']);
        
        header("Location: random.php");
        exit();
    }
}

$image = "home.png";
$dwn = "down.png";
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            #payment-methods {
                border: 1px solid #ccc;
                padding: 100px;
                margin: 20px 0;
                color: #333;
                background: green;
                border-radius: 30px;
            }
            .payment-option {
                margin: 10px 0;
            }
            .div1 {
                background-image: url('<?php echo $image; ?>');
                background-size: cover;
                background-position: center;
                text-align: center;
                padding: 100px 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 50vh;
                margin: 0;
            }
            button {
                background-color: black;
                color: #fff;
                border: none;
                padding: 10px 15px;
                cursor: pointer;
                border-radius: 5px;
            }
            #cancellation-message {
                margin-top: 10px;
                color: yellow;
            }
            h2 {
                color: white;
            }
            .order-summary {
                background-color: rgba(255, 255, 255, 0.9);
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 10px;
                color: black;
                text-align: left;
            }
            .order-summary h3 {
                margin-top: 0;
                color: #4CAF50;
            }
            .price-row {
                display: flex;
                justify-content: space-between;
                padding: 5px 0;
                border-bottom: 1px dotted #ccc;
            }
            .total-row {
                display: flex;
                justify-content: space-between;
                padding-top: 10px;
                font-weight: bold;
                font-size: 1.2em;
            }
        </style>
    </head>

    <body>
        <section class="div1">
            <div id="payment-methods">
                <h2>Complete Your Booking</h2>
                
                <?php if ($event_details): ?>
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="price-row">
                        <span>Event:</span>
                        <span><?php echo htmlspecialchars($event_details['name']); ?></span>
                    </div>
                    <div class="price-row">
                        <span>Date:</span>
                        <span><?php echo date('F j, Y', strtotime($event_details['event_date'])); ?> at <?php echo date('g:i A', strtotime($event_details['event_time'])); ?></span>
                    </div>
                    <div class="price-row">
                        <span>Tickets:</span>
                        <span><?php echo $num_tickets; ?> x $<?php echo number_format($event_details['price'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <form action="paymentmethod.php" method="POST">
                    <h2>Select a Payment Method</h2>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="credit-card" value="credit_card" required>
                        <label for="credit-card">Credit/Debit Card</label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="paypal" value="paypal">
                        <label for="paypal">PayPal</label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="bank-transfer" value="bank_transfer">
                        <label for="bank-transfer">Bank Transfer</label>
                    </div>
                    
                    <button type="submit" id="pay-button">Pay Now</button>
                </form>

                <div id="cancel-ticket" style="margin-top: 30px;">
                    <h2>Cancel Your Ticket</h2>
                    <form id="cancellation-form">
                        <label for="ticket-id">Enter Your Ticket ID:</label>
                        <input type="text" id="ticket-id" required>
                        <button type="submit">Cancel Ticket</button>
                    </form>
                    <div id="cancellation-message"></div>
                </div>
            </div>
        </section>

        <script>
            document.getElementById('cancellation-form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting the default way
                const ticketID = document.getElementById('ticket-id').value;

                if (ticketID) {
                    // display the cancellation message
                    document.getElementById('cancellation-message').textContent = 'Your ticket has been successfully cancelled! Ticket ID: ' + ticketID;
                }
            });
        </script>
    </body>   
</html>
<?php include 'footer.php'; ?>
