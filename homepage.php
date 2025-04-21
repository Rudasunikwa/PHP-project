<?php include 'header.php'; ?>
<?php
$image = "home.png";
$dwn = "down.png";
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
        .call p{
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
        .event-type{
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
h2{
    font-size: xx-large;
}
.event-card{
    background-color: #f0f8ff;
    padding: 40px;
    border-radius: 8px;
}

        
    </style>
</head>
    
<html>  
<body>       
<main >
    <section class="banner" >
        <h1>Welcome to the Best Event Management System</h1>
        <p>Find and book amazing events easily!</p>
    </section>

    <section class="upcoming-events">

        
        <h2>Upcoming Events</h2>
        <div class="event-list">
            <div class="event-card">
                <h3>Music Concert</h3>
                <p>Date: 25th March 2025</p>
                <a href="pages/event-details.php?id=1">View Details</a>
            </div>

            <div class="event-card">
                <h3>Tech Conference</h3>
                <p>Date: 10th April 2025</p>
                <a href="pages/event-details.php?id=2">View Details</a>
            </div>
        </div>
    </section>
</main>
<div class="down">
<div >
    <h2> Tell us what kind of events you want to host and we’ll help make it happen.</h2>
    <div class="event-types">
    <a href="music.php" class="event-type">Music</a>
    <a href="music.php"  class="event-type">Conference</a>
    <a href="music.php"  class="event-type">Party</a>
    <a href="wedding.php" class="event-type">Wedding</a>
    <a href="music.php" class="event-type">Workshop</a>
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

</div>
</body>
</html>
<?php include 'footer.php'; ?>

