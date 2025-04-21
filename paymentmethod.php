<?php include 'header.php'; ?>
<?php
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
            background-color:black;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        #cancellation-message {
            margin-top: 10px;
            color:yellow;
        }
        h2{
            color: white;
        }
        </style>
        
    </head>

    <body>
       
        <section class="div1">
                <div  id="payment-methods">
                    <h2>Select a Payment Method</h2>
                    <div class="payment-option" data-method="credit_card">
                        <input type="radio" name="payment" id="credit-card" value="credit_card">
                        <label for="credit-card">Credit/Debit Card</label>
                    </div>

                    <div class="payment-option" data-method="paypal">
                        <input type="radio" name="payment" id="paypal" value="paypal">
                        <label for="paypal">PayPal</label>
                    </div>

                    <div class="payment-option" data-method="bank_transfer">
                        <input type="radio" name="payment" id="bank-transfer" value="bank_transfer">
                        <label for="bank-transfer">Bank Transfer</label>
                    </div>
                    
                    <form action="random.php" method="POST">
                        <button type="submit" id="pay-button">Pay Now</button>
                    </form>

                    <div id="cancel-ticket">
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
