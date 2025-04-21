<?php include 'header.php'; ?>
<?php
$leader = "leader.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Your Event Management Company</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height:1.6;
            background-color: #f4f4f4;
        }

        /* header {
            background: #007BFF;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        } */

        section {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            max-width: 800px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        .team-member {
            margin: 20px 0;
            text-align: center;
            
        }

        .team-member img {
            width: 150px;
            height: auto;
            border-radius: 50%;
            background-image: url('<?php echo $leader; ?>');
            
        }

        .services ul {
            list-style-type: none;
            padding: 0;
        }

        .services li {
            background: #007BFF;
            color: white;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }

        /* footer {
            text-align: center;
            padding: 10px 0;
            background: #007BFF;
            color: white;
            position: absolute;
            width: 100%;
            bottom: 0;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        } */
    </style>
</head>
<body>

    <header>
        <h1>About Us</h1>
    </header>

    <section class="introduction">
        <h2>Welcome to MUSHUTI</h2>
        <p>At MUSHUTI, we are dedicated to making your event unforgettable. Whether it's a corporate gathering, wedding, or festival, we offer tailored solutions to meet your needs.</p>
    </section>

    <section class="mission">
        <h2>Our Mission</h2>
        <p>Our mission is to provide exceptional event management services that empower our clients to create memorable experiences. We believe in bringing visions to life with creativity and precision.</p>
    </section>

    <section class="team">
        <h2>Meet Our Team</h2>
        <div class="team-member">
            <img src="$leader" alt="Rudas">
            <h3>Rudas</h3>
            <p>Event Coordinator</p>
        </div>
        <div class="team-member">
            <img src="$leader" alt="Cyuzuzo">
            <h3>Cyuzuzo</h3>
            <p>Marketing Manager</p>
        </div>
        
    </section>

    <section class="services">
        <h2>Our Services</h2>
        <ul>
            <li>Event Planning</li>
            <li>Booking</li>
            <li>Event Management</li>
            <li>Event Marketing</li>
        </ul>
    </section>

    <section class="story">
        <h2>Our Story</h2>
        <p>MUSHUTI was founded in 2024 with a vision to transform the way events are managed. Our journey began with a small events, and since then we've grown to serve numerous clients across various industries.</p>
    </section>

    <section class="testimonials">
        <h2>What Our Clients Say</h2>
        <p>"The event ran seamlessly, thanks to the team at MUSHUTI. Highly recommend!" -</p>
        <p>"Professional, attentive, and creative. They exceeded our expectations!"-</p>
    </section>

    <section class="why-choose-us">
        <h2>Why Choose Us?</h2>
        <p>With years of experience, a dedicated team, and a passion for events, we are the perfect partner for your next gathering.</p>
    </section>

    <section class="contact">
        <h2>Get In Touch</h2>
        <p>Ready to plan your next event? <a href="homepage.php">Contact us today!</a></p>
    </section>

    <footer>
    <?php include 'footer.php'; ?>
    </footer>

</body>
</html>
