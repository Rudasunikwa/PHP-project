<?php 
include 'header.php';
include 'database.php'; // Include database connection

$image = "home.png";
$dwn = "down.png";

// Query to get the two closest upcoming events
$upcoming_events = [];
$current_date = date('Y-m-d'); // Get current date

if (isset($conn)) {
    $events_query = "SELECT id, name, description, event_date, event_time, location, price, image_path 
                    FROM events 
                    WHERE event_date >= '$current_date' 
                    ORDER BY event_date ASC 
                    LIMIT 2";
    
    $result = $conn->query($events_query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $upcoming_events[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .banner {
            background-image: url('<?php echo $image; ?>');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 0;
        }
        .down {
            background-image: url('<?php echo $dwn; ?>');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 200px 0;
        }

        .call {
            background-color: #f0f8ff; /* Light background color */
            padding: 20px;             /* Inner spacing */
            border-radius: 8px;        /* Rounded corners */
            text-align: center;         /* Center text */
            margin: 20px;              /* Outer spacing */
        }
        
        .call h5 {
            font-size: 24px;           /* Increase font size */
        }
        
        .call p {
            font-size: 20px;
        }

        .contact-list {
            display: flex;             /* Use flexbox for horizontal layout */
            justify-content: center;   /* Center items horizontally */
            flex-wrap: wrap;          /* Allow wrapping in smaller screens */
        }

        .contact-list span {
            margin: 0 15px;           /* Space between items */
        }

        .contact-list a {
            text-decoration: none;       /* Remove underline */
            color: #007bff;             /* Link color */
            display: flex;              /* Align icon and text */
            align-items: center;        /* Center items vertically */
        }

        .contact-list a i {
            margin-right: 5px;       /* Space between icon and text */
            font-size: 30px;
        }

        .contact-list a:hover {
            text-decoration: underline;  /* Underline on hover */
        }
        
        .event-type {
            background-color: #4CAF50;
            border: 5px solid #333;
            color: white;
            padding: 55px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 26px;
            margin: 20px 50px;
            cursor: pointer;
        }
        
        h2 {
            font-size: xx-large;
        }
        
        .event-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 20px auto;
            max-width: 1200px;
        }
        
        .event-card {
            background-color: #f0f8ff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 340px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .event-card h3 {
            color: #333;
            margin-top: 0;
            font-size: 22px;
        }
        
        .event-card p {
            color: #555;
            margin: 8px 0;
        }
        
        .event-card .event-date {
            font-weight: bold;
            color: #4CAF50;
        }
        
        .event-card .event-location {
            font-style: italic;
            color: #777;
        }
        
        .event-card .event-price {
            font-weight: bold;
            margin: 15px 0;
            font-size: 18px;
            color: #4CAF50;
        }
        
        .event-card .event-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        
        .event-card .event-btn:hover {
            background-color: #388E3C;
        }
        
        .see-more-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            background-color: #333;
            color: white;
            text-align: center;
            padding: 12px 0;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        
        .see-more-btn:hover {
            background-color: #555;
        }
        
        .upcoming-events {
            text-align: center;
            padding: 40px 20px;
        }
        
        .no-events {
            background-color: #f0f8ff;
            padding: 30px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 600px;
            text-align: center;
            color: #555;
        }

        .host-event-section {
            text-align: center;
            padding: 30px 0;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            max-width: 90%;
            margin: 0 auto 30px;
        }
        
        .host-event-section h2 {
            color: white;
            margin-bottom: 20px;
        }
        
        .event-types {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        
        .event-type {
            background-color: #4CAF50;
            border: 5px solid #333;
            color: white;
            padding: 35px 25px;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 10px;
            cursor: pointer;
            border-radius: 8px;
            width: 160px;
            height: 60px;
            transition: transform 0.3s, background-color 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .event-type::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .event-type:hover {
            transform: translateY(-5px);
        }
        
        .event-type:hover::before {
            transform: translateY(0);
        }
        
        .event-type.music { background-color: #9C27B0; }
        .event-type.conference { background-color: #2196F3; }
        .event-type.party { background-color: #FF9800; }
        .event-type.wedding { background-color: #E91E63; }
        .event-type.workshop { background-color: #607D8B; }
        
        .host-cta {
            margin-top: 30px;
        }
        
        .host-cta .event-btn {
            background-color: white;
            color: #333;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
            display: inline-block;
            margin-top: 15px;
        }
        
        .host-cta .event-btn:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
    
<html>  
<body>       
<main>
    <section class="banner">
        <h1>Welcome to the Best Event Management System</h1>
        <p>Find and book amazing events easily!</p>
    </section>

    <section class="upcoming-events">
        <h2>Upcoming Events</h2>
        
        <?php if (!empty($upcoming_events)): ?>
            <div class="event-list">
                <?php foreach ($upcoming_events as $event): ?>
                    <div class="event-card">
                        <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                        <p class="event-date">Date: <?php echo date('j F Y', strtotime($event['event_date'])); ?> at <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                        <p class="event-location">Location: <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><?php echo substr(htmlspecialchars($event['description']), 0, 100) . '...'; ?></p>
                        <p class="event-price">Price: $<?php echo number_format($event['price'], 2); ?></p>
                        <a href="event_details.php?id=<?php echo $event['id']; ?>" class="event-btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="feature.php" class="see-more-btn">See All Events</a>
        <?php else: ?>
            <div class="no-events">
                <p>No upcoming events at the moment. Check back soon!</p>
                <a href="feature.php" class="event-btn">Browse All Events</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<div class="down">
    <div class="host-event-section">
        <h2>Tell us what kind of event you want to host and we'll help make it happen</h2>
        <div class="event-types">
            <a href="post_event.php?type=music" class="event-type music">Music</a>
            <a href="post_event.php?type=conference" class="event-type conference">Conference</a>
            <a href="post_event.php?type=party" class="event-type party">Party</a>
            <a href="post_event.php?type=wedding" class="event-type wedding">Wedding</a>
            <a href="post_event.php?type=workshop" class="event-type workshop">Workshop</a>
        </div>
        <div class="host-cta">
            <p style="color: white;">Need to host another type of event?</p>
            <a href="post_event.php" class="event-btn">Host Any Event</a>
        </div>
    </div>
    
    <h2 class="stay"><strong>Stay getting information </strong></h2>
    <div class="event-card">
        <form>
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <input type="number" name="phone" placeholder="Enter your phone number" required><br>
            <button type="submit">Submit</button>
        </form>
    </div>
    <p> </p>
</div>

<div class="call">
        <h5>Looking for talk to someone?</h5>
        <p>If you are event creator or want any service and you would like to talk to someone <br>
    avout getting any information related to us, we are happy to have a conversation</p>
        <div class="contact-list">
            <span><a href="mailto:example@example.com"><i class="fas fa-envelope"></i> Email: rudaseric2022@gmail.com</a></span>
            <span><a href="tel:+91 (987) 7738-276"><i class="fas fa-phone-alt"></i> Call: +91 (987) 7738-276</a></span>
            <span><a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp: +91 (987) 7738-276</a></span>
            <span><a href="https://www.facebook.com/yourprofile" target="_blank"><i class="fab fa-facebook"></i> Facebook: Rudas</a></span>
        </div>
    </div>
</div>

</body>
</html>
<?php include 'footer.php'; ?>

