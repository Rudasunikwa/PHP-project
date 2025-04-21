<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .call {
            background-color: #f0f8ff; /* Light background color */
            padding: 20px;             /* Inner spacing */
            border: 1px solid #ccc;    /* Light border */
            border-radius: 8px;        /* Rounded corners */
            text-align: center;         /* Center text */
            margin: 20px;              /* Outer spacing */
        }

        .call h5 {
            font-size: 24px;           /* Increase font size */
            color: #333;               /* Dark text color */
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
        }

        .contact-list a:hover {
            text-decoration: underline;  /* Underline on hover */
        }
    </style>
</head>
<body>
    <div class="call">
        <h5>Looking for talk to someone?</h5>
        <div class="contact-list">
            <span><a href="mailto:example@example.com"><i class="fas fa-envelope"></i> Email: example@example.com</a></span>
            <span><a href="tel:+1234567890"><i class="fas fa-phone-alt"></i> Call: +1 (234) 567-890</a></span>
            <span><a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp: +1 (234) 567-890</a></span>
            <span><a href="https://www.facebook.com/yourprofile" target="_blank"><i class="fab fa-facebook"></i> Facebook: Your Profile</a></span>
            <span><a> <i class="fab fa-linkdin"></i>Linkdin: Rudas </a></span>
        </div>
    </div>
</body>
</html>