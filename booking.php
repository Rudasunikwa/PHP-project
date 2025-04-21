<?php include 'header.php'; ?>
<html>
    <head>

    </head>
    <body>
        <!--
    Booking form
        -->


        <?php
// Sample event data (you would likely retrieve this from a database)
$events = array(
    array("id" => 1, "name" => "Concert in the Park", "date" => "2025-07-15", "time" => "7:00 PM"),
    array("id" => 2, "name" => "Art Exhibition", "date" => "2024-03-22", "time" => "2:00 PM"),
    array("id" => 3, "name" => "Comedy Show", "date" => "2025-04-29", "time" => "8:00 PM"),
);

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Event Booking</title>";
// Basic CSS styling (you'll likely want to improve this)
echo "<style>";
echo "body { font-family: sans-serif; }";
echo "form { display: flex; flex-direction: column; width: 300px; margin: 20px auto; }";
echo "label { margin-bottom: 5px; }";
echo "select, input[type='number'] { padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; }";
echo "input[type='submit'] { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }";
echo "h1 { text-align: center; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>Event Booking</h1>";
// Form action would point to your booking processing script

echo "<form action='paymentmethod.php' method='post'>"; 
echo "<label for='event'>Select Event:</label>";
echo "<select name='event_id' id='event'>";
foreach ($events as $event) {
    echo "<option value='" . $event['id'] . "'>" . $event['name'] . " (" . $event['date'] . " " . $event['time'] . ")</option>";
}
echo "</select>";

echo "<label for='tickets'>Number of Tickets:</label>";
echo "<input type='number' name='num_tickets' id='tickets' min='1' required>";

echo "<input type='submit' value='Book Now'>";

echo "</form>";

echo "</body>";
echo "</html>";

?>
        <!-- <form method="POST">
            <input type="text" name="name" placeholder="Name"><br>
            <input type="email" name="email" placeholder="Email"><br>
            <input type="text" name="phone" placeholder="Phone"><br>
            <input type="text" name="address" placeholder="Address"><br>
            <input type="text" name="country" placeholder="Country"><br>
            <input type="date" name="date" placeholder="date"><br>
            <input type="time" name="time" placeholder="time"><br>
            <input type="number" name="number" placeholder="number of people"><br>
            <button type="submit">Book</button>
         -->
    </body>
</html>
<?php include 'footer.php'; ?>