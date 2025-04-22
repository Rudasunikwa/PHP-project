<?php 
include 'header.php';
include 'auth.php';
include 'database.php';

// Require login to post events
if (!isLoggedIn()) {
    // Store the event type in session if provided
    if (isset($_GET['type'])) {
        $_SESSION['event_post_type'] = $_GET['type'];
    }
    
    // Redirect to login with a message
    header("Location: login.php?redirect=post_event.php");
    exit();
}

// Get event type from URL parameter or session or default to "general"
$event_type = isset($_GET['type']) ? $_GET['type'] : (isset($_SESSION['event_post_type']) ? $_SESSION['event_post_type'] : "general");
// Clear session event type if it exists
if (isset($_SESSION['event_post_type'])) {
    unset($_SESSION['event_post_type']);
}

// Set page title based on event type
$type_names = [
    'music' => 'Music Event',
    'conference' => 'Conference',
    'party' => 'Party',
    'wedding' => 'Wedding',
    'workshop' => 'Workshop',
    'general' => 'General Event'
];

$page_title = isset($type_names[$event_type]) ? $type_names[$event_type] : 'Event';

// Process form submission
$submission_message = '';
$submission_status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_event'])) {
    // Collect and sanitize form data
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = htmlspecialchars($_POST['location']);
    $organizer = htmlspecialchars($_POST['organizer']);
    $price = floatval($_POST['price']);
    $capacity = intval($_POST['capacity']);
    $contact_email = htmlspecialchars($_POST['contact_email']);
    $contact_phone = htmlspecialchars($_POST['contact_phone']);
    $event_category = htmlspecialchars($_POST['event_category']);
    $user_id = $_SESSION['user_id'];
    
    // Default image path based on event type
    $image_path = $event_type . '.png'; 
    if (!file_exists($image_path)) {
        $image_path = 'home.png'; // Default image if type image doesn't exist
    }
    
    // Handle image upload if provided
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($_FILES['event_image']['type'], $allowed_types) && $_FILES['event_image']['size'] <= $max_size) {
            $file_extension = pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION);
            $new_filename = 'event_' . time() . '.' . $file_extension;
            $upload_path = $new_filename; // In a real app, you'd use a dedicated uploads directory
            
            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $upload_path)) {
                $image_path = $upload_path;
            }
        }
    }
    
    // Save event to database
    $stmt = $conn->prepare("INSERT INTO events (name, description, event_date, event_time, location, price, capacity, image_path, event_category, organizer_id, contact_email, contact_phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    
    $stmt->bind_param("ssssssississ", 
        $title, 
        $description, 
        $event_date, 
        $event_time, 
        $location, 
        $price, 
        $capacity, 
        $image_path,
        $event_category,
        $user_id,
        $contact_email,
        $contact_phone
    );
    
    if ($stmt->execute()) {
        $event_id = $conn->insert_id; // Get the ID of the newly created event
        $submission_status = 'success';
        $submission_message = "Thank you! Your event \"" . htmlspecialchars($title) . "\" has been submitted successfully! Our team will review it shortly.";
        
        // Set a session message to display on the my_events page
        $_SESSION['event_success'] = $submission_message;
        
        // Redirect to my_events.php after a short delay (3 seconds)
        echo "<script>
            setTimeout(function() {
                window.location.href = 'my_events.php';
            }, 3000);
        </script>";
    } else {
        $submission_status = 'error';
        $submission_message = "Error submitting your event: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a <?php echo $page_title; ?></title>
    <style>
        .header-banner {
            background-image: url('<?php echo $event_type; ?>.png'), url('home.png');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 60px 0;
            margin-bottom: 30px;
            position: relative;
        }
        
        .header-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);  /* Dark overlay */
            z-index: 1;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .header-banner h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .header-banner p {
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .form-container {
            max-width: 800px;
            margin: 0 auto 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 1.8em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #388E3C;
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        
        .event-guidelines {
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .event-guidelines h3 {
            margin-top: 0;
            color: #4CAF50;
        }
        
        .event-guidelines ul {
            padding-left: 20px;
        }
        
        .event-guidelines li {
            margin-bottom: 5px;
        }
        
        .form-note {
            font-size: 0.9em;
            color: #777;
            margin-top: 5px;
        }
        
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <h1>Host Your <?php echo $page_title; ?></h1>
            <p>Share your event with our community. Fill out the form below with your event details, and our team will review your submission.</p>
        </div>
    </div>
    
    <div class="form-container">
        <?php if(!empty($submission_message)): ?>
            <div class="message <?php echo $submission_status; ?>">
                <?php echo $submission_message; ?>
            </div>
        <?php endif; ?>
        
        <h2 class="form-title">Tell Us About Your Event</h2>
        
        <div class="event-guidelines">
            <h3>Guidelines for Event Submission</h3>
            <ul>
                <li>Provide accurate and detailed information about your event</li>
                <li>Include contact information so attendees can reach you with questions</li>
                <li>Your event will be reviewed by our team before being published</li>
                <li>Once approved, your event will be visible on our platform</li>
            </ul>
        </div>
        
        <form action="post_event.php?type=<?php echo $event_type; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="required-field">Event Title</label>
                <input type="text" id="title" name="title" required>
                <div class="form-note">Choose a clear, descriptive title for your event</div>
            </div>
            
            <div class="form-group">
                <label for="description" class="required-field">Event Description</label>
                <textarea id="description" name="description" required></textarea>
                <div class="form-note">Describe what attendees can expect at your event</div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="event_date" class="required-field">Event Date</label>
                    <input type="date" id="event_date" name="event_date" required>
                </div>
                
                <div class="form-group">
                    <label for="event_time" class="required-field">Event Time</label>
                    <input type="time" id="event_time" name="event_time" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="location" class="required-field">Event Location</label>
                <input type="text" id="location" name="location" required>
                <div class="form-note">Physical address or online platform for virtual events</div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price" class="required-field">Ticket Price ($)</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" required>
                    <div class="form-note">Enter 0 for free events</div>
                </div>
                
                <div class="form-group">
                    <label for="capacity" class="required-field">Event Capacity</label>
                    <input type="number" id="capacity" name="capacity" min="1" required>
                    <div class="form-note">Maximum number of attendees</div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="event_category" class="required-field">Event Category</label>
                <select id="event_category" name="event_category" required>
                    <option value="">Select a category</option>
                    <option value="music" <?php echo ($event_type == 'music') ? 'selected' : ''; ?>>Music</option>
                    <option value="conference" <?php echo ($event_type == 'conference') ? 'selected' : ''; ?>>Conference</option>
                    <option value="party" <?php echo ($event_type == 'party') ? 'selected' : ''; ?>>Party</option>
                    <option value="wedding" <?php echo ($event_type == 'wedding') ? 'selected' : ''; ?>>Wedding</option>
                    <option value="workshop" <?php echo ($event_type == 'workshop') ? 'selected' : ''; ?>>Workshop</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="organizer" class="required-field">Organizer Name</label>
                <input type="text" id="organizer" name="organizer" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contact_email" class="required-field">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" required>
                </div>
                
                <div class="form-group">
                    <label for="contact_phone" class="required-field">Contact Phone</label>
                    <input type="tel" id="contact_phone" name="contact_phone" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="event_image">Event Image</label>
                <input type="file" id="event_image" name="event_image" accept="image/*">
                <div class="form-note">Upload an image to promote your event (JPEG, PNG, or GIF, max 5MB)</div>
            </div>
            
            <input type="hidden" name="event_type" value="<?php echo $event_type; ?>">
            <button type="submit" name="submit_event" class="submit-btn">Submit Event for Approval</button>
        </form>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>