<?php 
include 'header.php';
include 'auth.php';

// Require login to access this page
requireLogin();
?>
<html>
    <head>
    <style>
        body { font-family: sans-serif; }
        form { display: flex; flex-direction: column; width: 300px; margin: 20px auto; }
        label { margin-bottom: 5px; }
        select, input[type='number'] { padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; }
        input[type='submit'] { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        h1 { text-align: center; }
    </style>
    </head>
    <body>
        <h1>Event Booking</h1>

        <?php
        // Sample event data (you would likely retrieve this from a database)
        $events = array(
            array("id" => 1, "name" => "Concert in the Park", "date" => "2025-07-15", "time" => "7:00 PM"),
            array("id" => 2, "name" => "Art Exhibition", "date" => "2024-03-22", "time" => "2:00 PM"),
            array("id" => 3, "name" => "Comedy Show", "date" => "2025-04-29", "time" => "8:00 PM"),
        );
        ?>

        <form action='paymentmethod.php' method='post'>
            <label for='event'>Select Event:</label>
            <select name='event_id' id='event'>
                <?php foreach ($events as $event): ?>
                    <option value='<?php echo $event['id']; ?>'>
                        <?php echo $event['name'] . " (" . $event['date'] . " " . $event['time'] . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for='tickets'>Number of Tickets:</label>
            <input type='number' name='num_tickets' id='tickets' min='1' required>

            <input type='hidden' name='user_id' value='<?php echo getCurrentUserId(); ?>'>
            <input type='submit' value='Book Now'>
        </form>
    </body>
</html>
<?php include 'footer.php'; ?>