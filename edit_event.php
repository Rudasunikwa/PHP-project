<?php 
include 'header.php';
include 'auth.php';
include 'database.php';

// Require login to edit events
if (!isLoggedIn()) {
    header("Location: login.php?redirect=my_events.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error_message = '';
$event = null;

// Fetch the event if it exists and belongs to the user
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND organizer_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        $error_message = "Event not found or you don't have permission to edit it.";
    }
} else {
    $error_message = "Invalid event ID.";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event']) && $event) {
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
    
    // Keep the existing image path by default
    $image_path = $event['image_path'];
    
    // Handle image upload if provided
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($_FILES['event_image']['type'], $allowed_types) && $_FILES['event_image']['size'] <= $max_size) {
            $file_extension = pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION);
            $new_filename = 'event_' . time() . '.' . $file_extension;
            $upload_path = $new_filename; // In a real app, you'd use a dedicated uploads directory
            
            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $upload_path)) {
                // If this is a new image, delete the old one (except default images)
                if (!in_array($image_path, ['home.png', 'music.png', 'wedding.png', 'conference.png', 'party.png', 'workshop.png']) && 
                    file_exists($image_path)) {
                    unlink($image_path);
                }
                $image_path = $upload_path;
            }
        }
    }
    
    // Update event in database - set status back to 'pending' if content changed
    $update_stmt = $conn->prepare("UPDATE events SET 
                                name = ?, 
                                description = ?, 
                                event_date = ?, 
                                event_time = ?, 
                                location = ?, 
                                price = ?, 
                                capacity = ?, 
                                image_path = ?, 
                                event_category = ?, 
                                contact_email = ?, 
                                contact_phone = ?,
                                status = CASE
                                    WHEN status = 'approved' AND (
                                        name != ? OR 
                                        description != ? OR 
                                        event_date != ? OR 
                                        event_time != ? OR 
                                        location != ? OR 
                                        price != ? OR 
                                        event_category != ?
                                    ) THEN 'pending'
                                    ELSE status
                                END
                                WHERE id = ? AND organizer_id = ?");
    
    $update_stmt->bind_param("sssssdssssssssssdiii", 
        $title, 
        $description, 
        $event_date, 
        $event_time, 
        $location, 
        $price, 
        $capacity, 
        $image_path,
        $event_category,
        $contact_email,
        $contact_phone,
        $event['name'],
        $event['description'],
        $event['event_date'],
        $event['event_time'],
        $event['location'],
        $event['price'],
        $event['event_category'],
        $event_id,
        $user_id
    );
    
    if ($update_stmt->execute()) {
        $_SESSION['event_success'] = "Your event has been updated successfully! If you made significant changes, it may need to be reviewed again.";
        header("Location: my_events.php");
        exit();
    } else {
        $error_message = "Error updating event: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .header-banner {
            background-image: url('<?php echo $event ? $event['image_path'] : 'home.png'; ?>');
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
        
        .cancel-btn {
            background-color: #9e9e9e;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .cancel-btn:hover {
            background-color: #7d7d7d;
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
        
        .form-note {
            font-size: 0.9em;
            color: #777;
            margin-top: 5px;
        }
        
        .required-field::after {
            content: " *";
            color: red;
        }
        
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .current-status {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        
        .status-pending {
            color: #856404;
            background-color: #fff3cd;
        }
        
        .status-approved {
            color: #155724;
            background-color: #d4edda;
        }
        
        .status-rejected {
            color: #721c24;
            background-color: #f8d7da;
        }
        
        .current-image {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .current-image img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <h1>Edit Your Event</h1>
            <p>Update your event details below. If you make significant changes, your event may need to be reviewed again.</p>
        </div>
    </div>
    
    <div class="form-container">
        <?php if (!empty($error_message)): ?>
            <div class="message error">
                <?php echo $error_message; ?>
                <p><a href="my_events.php">Return to My Events</a></p>
            </div>
        <?php elseif ($event): ?>
            <h2 class="form-title">Edit Event: <?php echo htmlspecialchars($event['name']); ?></h2>
            
            <div class="current-status status-<?php echo strtolower($event['status']); ?>">
                Current Status: <?php echo ucfirst($event['status']); ?>
                <?php if ($event['status'] === 'approved'): ?>
                <p style="font-size: 0.9em; margin-top: 5px; font-weight: normal;">
                    Note: Making significant changes will require re-approval of your event.
                </p>
                <?php endif; ?>
            </div>
            
            <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="required-field">Event Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description" class="required-field">Event Description</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="event_date" class="required-field">Event Date</label>
                        <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_time" class="required-field">Event Time</label>
                        <input type="time" id="event_time" name="event_time" value="<?php echo $event['event_time']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="location" class="required-field">Event Location</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="required-field">Ticket Price ($)</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $event['price']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="capacity" class="required-field">Event Capacity</label>
                        <input type="number" id="capacity" name="capacity" min="1" value="<?php echo $event['capacity']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="event_category" class="required-field">Event Category</label>
                    <select id="event_category" name="event_category" required>
                        <option value="music" <?php echo ($event['event_category'] === 'music') ? 'selected' : ''; ?>>Music</option>
                        <option value="conference" <?php echo ($event['event_category'] === 'conference') ? 'selected' : ''; ?>>Conference</option>
                        <option value="party" <?php echo ($event['event_category'] === 'party') ? 'selected' : ''; ?>>Party</option>
                        <option value="wedding" <?php echo ($event['event_category'] === 'wedding') ? 'selected' : ''; ?>>Wedding</option>
                        <option value="workshop" <?php echo ($event['event_category'] === 'workshop') ? 'selected' : ''; ?>>Workshop</option>
                        <option value="other" <?php echo ($event['event_category'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="organizer" class="required-field">Organizer Name</label>
                    <input type="text" id="organizer" name="organizer" value="<?php echo htmlspecialchars($event['organizer_name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_email" class="required-field">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($event['contact_email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_phone" class="required-field">Contact Phone</label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($event['contact_phone']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="event_image">Event Image</label>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image">
                    </div>
                    <input type="file" id="event_image" name="event_image" accept="image/*">
                    <div class="form-note">Upload a new image to replace the current one (JPEG, PNG, or GIF, max 5MB)</div>
                </div>
                
                <div class="btn-container">
                    <button type="submit" name="update_event" class="submit-btn">Update Event</button>
                    <a href="my_events.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>