<?php include 'header.php'; ?>
<?php
$image = "home.png";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Post Event</title>
        <style>
            .d1{
                background-color: #f0f8ff; /* Light background color */
                padding: 80px;             /* Inner spacing */
                border-radius: 8px;        /* Rounded corners */
                text-align: center;         /* Center text */
                margin: 0px;              /* Outer spacing */
            }
            .p1{
                font-size: 24px;           /* Increase font size */
                text-align: center; 
                padding: 20px; /* Adding padding for spacing */
                font-size:20px;
                
            }
            .class1{
                background-image: url('<?php echo $image; ?>');
                background-size: cover;
                background-position: center;
                color: white;
                text-align: center;
                padding: 50px 0;
            }
        </style>
    </head>
    <body>
    <div class="class1">
    <p class="p1">
    Welcome to your premier destination for seamless event management!Whether you're planning an intimate gathering, a corporate function, <br> 
    or a grand celebration, our platform is designed to simplify the entire process. With user-friendly tools and resources at your fingertips, <br>
    you can effortlessly create, promote, and manage your events from start to finish. Explore customizable templates, expert tips, and vendor <br>
    connections that will elevate your event experience to the next level. Join our community of satisfied clients who have transformed their <br>
    visions into unforgettable realities. Take the stress out of planning and let us help you create memorable moments that will resonate for years to come!</p>
    </div>
        <div class="d1">
        <form action="login.php" method="POST">
        <label>Event Title:</label>
        <input type="text" name="title" required><br>

        <label>Description:</label>
        <textarea name="description" required></textarea><br>

        <label>Date:</label>
        <input type="date" name="date" required><br>

        <label>Location:</label>
        <input type="text" name="location" required><br>

        <label>Organizer Name:</label>
        <input type="text" name="organizer" required><br>

        <input type="submit" name="submit" value="Post Event">
    </form>
        </div>
    </body>
</html>
<?php include 'footer.php'; ?>