<?php
include 'header.php';
include 'auth.php';
include 'database.php';

// Require login to view events
if (!isLoggedIn()) {
    header("Location: login.php?redirect=my_events.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$events = [];
$error_message = '';

// Handle event deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $event_id = $_GET['delete'];
    
    // Check if the event belongs to the user
    $check_stmt = $conn->prepare("SELECT id FROM events WHERE id = ? AND organizer_id = ?");
    $check_stmt->bind_param("ii", $event_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // The event belongs to the user, proceed with deletion
        $delete_stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $delete_stmt->bind_param("i", $event_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['event_success'] = "Event successfully deleted.";
            header("Location: my_events.php");
            exit();
        } else {
            $error_message = "Error deleting event: " . $conn->error;
        }
    } else {
        $error_message = "You don't have permission to delete this event.";
    }
}

// Fetch user's events
$stmt = $conn->prepare("SELECT * FROM events WHERE organizer_id = ? ORDER BY event_date DESC");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    $error_message = "Error fetching events: " . $conn->error;
}

// Success message from session (from post_event.php or edit_event.php)
$success_message = isset($_SESSION['event_success']) ? $_SESSION['event_success'] : '';
unset($_SESSION['event_success']); // Clear after displaying once
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Event Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .header-banner {
            background-image: url('home.png');
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
            background: rgba(0, 0, 0, 0.6);
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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
        
        .event-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .event-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .event-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .event-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8em;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #FFD700;
            color: #333;
        }
        
        .status-approved {
            background-color: #4CAF50;
            color: white;
        }
        
        .status-rejected {
            background-color: #F44336;
            color: white;
        }
        
        .event-details {
            padding: 20px;
        }
        
        .event-details h3 {
            margin-top: 0;
            font-size: 1.4em;
            color: #333;
            margin-bottom: 10px;
        }
        
        .event-meta {
            margin-bottom: 15px;
            font-size: 0.9em;
            color: #666;
        }
        
        .event-meta i {
            width: 20px;
            color: #4CAF50;
        }
        
        .event-description {
            margin-bottom: 15px;
            line-height: 1.4;
            color: #555;
        }
        
        .event-actions {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .event-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
        }
        
        .event-btn i {
            margin-right: 5px;
        }
        
        .view-btn {
            background-color: #2196F3;
            color: white;
        }
        
        .view-btn:hover {
            background-color: #0b7dda;
        }
        
        .edit-btn {
            background-color: #FFC107;
            color: #333;
        }
        
        .edit-btn:hover {
            background-color: #e6af00;
        }
        
        .delete-btn {
            background-color: #F44336;
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        
        .post-event-cta {
            text-align: center;
            margin: 40px auto;
        }
        
        .post-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        .post-btn:hover {
            background-color: #388E3C;
        }
        
        .no-events {
            text-align: center;
            padding: 40px 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin: 30px auto;
            max-width: 600px;
        }
        
        .no-events h3 {
            margin-top: 0;
            color: #555;
        }
        
        .delete-confirm-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        
        .modal-content h3 {
            margin-top: 0;
            color: #F44336;
        }
        
        .modal-actions {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .cancel-btn {
            background-color: #9e9e9e;
            color: white;
        }
        
        .cancel-btn:hover {
            background-color: #7d7d7d;
        }
    </style>
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <h1>My Events</h1>
            <p>Manage all your events in one place. View status, edit details, or create new events.</p>
        </div>
    </div>
    
    <div class="container">
        <?php if (!empty($success_message)): ?>
            <div class="message success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="message error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="post-event-cta">
            <h2>Ready to host another event?</h2>
            <p>Create a new event and share it with our community.</p>
            <a href="post_event.php" class="post-btn">
                <i class="fas fa-plus-circle"></i> Create New Event
            </a>
        </div>
        
        <?php if (empty($events)): ?>
            <div class="no-events">
                <h3>You haven't created any events yet</h3>
                <p>When you create events, they will appear here for easy management.</p>
                <a href="post_event.php" class="event-btn view-btn">
                    <i class="fas fa-plus-circle"></i> Create Your First Event
                </a>
            </div>
        <?php else: ?>
            <h2>Your Events</h2>
            <div class="event-list">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="event-image" style="background-image: url('<?php echo htmlspecialchars($event['image_path']); ?>')">
                            <div class="event-status status-<?php echo strtolower($event['status']); ?>">
                                <?php echo ucfirst($event['status']); ?>
                            </div>
                        </div>
                        <div class="event-details">
                            <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                            <div class="event-meta">
                                <p><i class="far fa-calendar-alt"></i> <?php echo date('j F Y', strtotime($event['event_date'])); ?> at <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                                <p><i class="fas fa-tag"></i> Category: <?php echo ucfirst(htmlspecialchars($event['event_category'])); ?></p>
                                <p><i class="fas fa-dollar-sign"></i> Price: $<?php echo number_format($event['price'], 2); ?></p>
                            </div>
                            <div class="event-description">
                                <?php echo substr(htmlspecialchars($event['description']), 0, 100) . '...'; ?>
                            </div>
                            <div class="event-actions">
                                <a href="event_details.php?id=<?php echo $event['id']; ?>" class="event-btn view-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <?php if ($event['status'] !== 'approved'): ?>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="event-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <?php endif; ?>
                                <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $event['id']; ?>, '<?php echo addslashes($event['name']); ?>')" class="event-btn delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-confirm-modal">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p id="deleteMessage">Are you sure you want to delete this event?</p>
            <div class="modal-actions">
                <a href="#" id="confirmDelete" class="event-btn delete-btn">
                    <i class="fas fa-trash"></i> Delete
                </a>
                <button onclick="closeModal()" class="event-btn cancel-btn">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete(eventId, eventName) {
            const modal = document.getElementById('deleteModal');
            const message = document.getElementById('deleteMessage');
            const confirmBtn = document.getElementById('confirmDelete');
            
            message.textContent = `Are you sure you want to delete the event "${eventName}"?`;
            confirmBtn.href = `my_events.php?delete=${eventId}`;
            
            modal.style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modal if user clicks outside the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
<?php include 'footer.php'; ?>