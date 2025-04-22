<?php include 'header.php'; ?>

<div class="contact-container">
    <h1 class="page-title">Contact Us</h1>
    <p class="subtitle">We'd love to hear from you! Please fill out the form below to get in touch.</p>
    
    <div class="contact-content">
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="contact.php" method="post">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" name="submit" class="submit-btn">Send Message</button>
            </form>
            
            <?php
            // Process form submission
            if (isset($_POST['submit'])) {
                $name = htmlspecialchars($_POST['name']);
                $email = htmlspecialchars($_POST['email']);
                $subject = htmlspecialchars($_POST['subject']);
                $message = htmlspecialchars($_POST['message']);
                
                // Here you would typically send an email or save to database
                // For now, just show a confirmation message
                echo '<div class="success-message">Thank you for your message, ' . $name . '! We will get back to you soon.</div>';
            }
            ?>
        </div>
        
        <div class="contact-info">
            <h2>Contact Information</h2>
            <div class="info-item">
                <strong>Address:</strong>
                <p>Jalandhar - Delhi, Grand Trunk Rd<br>Phagwara, Punjab 144411</p>
            </div>
            
            <div class="info-item">
                <strong>Phone:</strong>
                <p>+91 98777 38276</p>
            </div>
            
            <div class="info-item">
                <strong>Email:</strong>
                <p>rudaseric@gmail.com</p>
            </div>
            
            <div class="info-item">
                <strong>Office Hours:</strong>
                <p>Monday - Friday: 9:00 AM - 5:00 PM<br>
                   Saturday: 10:00 AM - 2:00 PM<br>
                   Sunday: Closed</p>
            </div>
        </div>
    </div>
</div>

<style>
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-title {
        text-align: center;
        color: #333;
        margin: 30px 0;
        font-size: 2.5em;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 40px;
        font-size: 1.2em;
    }
    
    .contact-content {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .contact-form {
        flex: 1 1 60%;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .contact-info {
        flex: 1 1 30%;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }
    
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    .submit-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .submit-btn:hover {
        background-color: #388E3C;
    }
    
    .success-message {
        margin-top: 20px;
        padding: 15px;
        background-color: #dff0d8;
        color: #3c763d;
        border-radius: 4px;
    }
    
    .info-item {
        margin-bottom: 20px;
    }
    
    .info-item strong {
        display: block;
        color: #4CAF50;
        margin-bottom: 5px;
    }
    
    .info-item p {
        color: #666;
        line-height: 1.5;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .contact-content {
            flex-direction: column;
        }
    }
</style>

<?php include 'footer.php'; ?>