<?php
include 'header.php';
include 'auth.php';
include 'database.php';

// Require admin access
if (!isAdmin()) {
    header("Location: homepage.php");
    exit();
}

// Process event actions
$success_message = '';
$error_message = '';

// Handle event approval
if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $event_id = $_GET['approve'];
    $stmt = $conn->prepare("UPDATE events SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    
    if ($stmt->execute()) {
        $success_message = "Event approved successfully!";
    } else {
        $error_message = "Error approving event: " . $conn->error;
    }
}

// Handle event rejection
if (isset($_GET['reject']) && is_numeric($_GET['reject'])) {
    $event_id = $_GET['reject'];
    $stmt = $conn->prepare("UPDATE events SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    
    if ($stmt->execute()) {
        $success_message = "Event rejected successfully!";
    } else {
        $error_message = "Error rejecting event: " . $conn->error;
    }
}

// Handle event deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $event_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    
    if ($stmt->execute()) {
        $success_message = "Event deleted successfully!";
    } else {
        $error_message = "Error deleting event: " . $conn->error;
    }
}

// Get filter values
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the query based on filters
$query = "SELECT e.*, u.username as organizer_username 
          FROM events e 
          LEFT JOIN users u ON e.organizer_id = u.id 
          WHERE 1=1";

$params = [];
$param_types = "";

if ($status_filter !== 'all') {
    $query .= " AND e.status = ?";
    $params[] = $status_filter;
    $param_types .= "s";
}

if (!empty($search_term)) {
    $search_param = "%" . $search_term . "%";
    $query .= " AND (e.name LIKE ? OR e.description LIKE ? OR e.location LIKE ?)";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $param_types .= "sss";
}

$query .= " ORDER BY e.event_date DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .page-title {
            font-size: 28px;
            color: #333;
        }
        
        .filter-controls {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-label {
            font-weight: bold;
            color: #555;
        }
        
        select, input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .filter-btn:hover {
            background-color: #388E3C;
        }
        
        .reset-btn {
            background-color: #9e9e9e;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        
        .event-count {
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
        }
        
        .events-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            margin-bottom: 30px;
        }
        
        .events-table th {
            background-color: #f5f5f5;
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .events-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .events-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .event-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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
        
        .action-btn {
            display: inline-block;
            padding: 6px 10px;
            margin: 0 3px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            text-align: center;
            font-weight: bold;
        }
        
        .approve-btn {
            background-color: #4CAF50;
        }
        
        .approve-btn:hover {
            background-color: #388E3C;
        }
        
        .reject-btn {
            background-color: #F44336;
        }
        
        .reject-btn:hover {
            background-color: #D32F2F;
        }
        
        .view-btn {
            background-color: #2196F3;
        }
        
        .view-btn:hover {
            background-color: #0b7dda;
        }
        
        .edit-btn {
            background-color: #FF9800;
        }
        
        .edit-btn:hover {
            background-color: #F57C00;
        }
        
        .delete-btn {
            background-color: #9E9E9E;
        }
        
        .delete-btn:hover {
            background-color: #757575;
        }
        
        .no-events {
            padding: 30px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 8px;
            color: #666;
        }
        
        .truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
        
        .date-cell {
            white-space: nowrap;
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
        
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            flex: 1;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-pending .stat-value {
            color: #FFC107;
        }
        
        .stat-approved .stat-value {
            color: #4CAF50;
        }
        
        .stat-rejected .stat-value {
            color: #F44336;
        }
        
        .stat-all .stat-value {
            color: #2196F3;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="page-header">
            <h1 class="page-title">Admin - Manage Events</h1>
        </div>
        
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
        
        <!-- Event Statistics -->
        <?php
            $stats = [
                'all' => 0,
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0
            ];
            
            // Count events by status
            $stats_query = "SELECT status, COUNT(*) as count FROM events GROUP BY status";
            $stats_result = $conn->query($stats_query);
            
            while ($row = $stats_result->fetch_assoc()) {
                $stats[$row['status']] = $row['count'];
            }
            
            // Get total count
            $total_query = "SELECT COUNT(*) as total FROM events";
            $total_result = $conn->query($total_query);
            $total_row = $total_result->fetch_assoc();
            $stats['all'] = $total_row['total'];
        ?>
        
        <div class="stats-container">
            <div class="stat-card stat-all">
                <div class="stat-value"><?php echo $stats['all']; ?></div>
                <div class="stat-label">Total Events</div>
            </div>
            <div class="stat-card stat-pending">
                <div class="stat-value"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card stat-approved">
                <div class="stat-value"><?php echo $stats['approved']; ?></div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card stat-rejected">
                <div class="stat-value"><?php echo $stats['rejected']; ?></div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filter-controls">
            <form action="admin_events.php" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%;">
                <div class="filter-group">
                    <label for="status" class="filter-label">Status:</label>
                    <select name="status" id="status">
                        <option value="all" <?php echo ($status_filter === 'all') ? 'selected' : ''; ?>>All</option>
                        <option value="pending" <?php echo ($status_filter === 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo ($status_filter === 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo ($status_filter === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>
                <div class="filter-group" style="flex-grow: 1;">
                    <label for="search" class="filter-label">Search:</label>
                    <input type="text" name="search" id="search" placeholder="Search events..." value="<?php echo htmlspecialchars($search_term); ?>" style="flex-grow: 1; min-width: 200px;">
                </div>
                <div class="filter-group">
                    <button type="submit" class="filter-btn">Apply Filters</button>
                    <a href="admin_events.php" class="reset-btn">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Events List -->
        <div class="event-count">
            <?php echo count($events); ?> event<?php echo (count($events) != 1) ? 's' : ''; ?> found
        </div>
        
        <?php if (empty($events)): ?>
            <div class="no-events">
                <h3>No events found matching your criteria</h3>
                <p>Try changing your filters or add new events.</p>
            </div>
        <?php else: ?>
            <table class="events-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event Name</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Organizer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $event['id']; ?></td>
                            <td class="truncate" title="<?php echo htmlspecialchars($event['name']); ?>">
                                <?php echo htmlspecialchars($event['name']); ?>
                            </td>
                            <td class="date-cell">
                                <?php echo date('j M Y', strtotime($event['event_date'])); ?><br>
                                <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                            </td>
                            <td class="truncate" title="<?php echo htmlspecialchars($event['location']); ?>">
                                <?php echo htmlspecialchars($event['location']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($event['organizer_username'] ?? 'Unknown'); ?></td>
                            <td>
                                <span class="event-status status-<?php echo strtolower($event['status']); ?>">
                                    <?php echo ucfirst($event['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($event['status'] === 'pending'): ?>
                                    <a href="admin_events.php?approve=<?php echo $event['id']; ?>" class="action-btn approve-btn" title="Approve Event">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                    <a href="admin_events.php?reject=<?php echo $event['id']; ?>" class="action-btn reject-btn" title="Reject Event">
                                        <i class="fas fa-times"></i> Reject
                                    </a>
                                <?php endif; ?>
                                <a href="event_details.php?id=<?php echo $event['id']; ?>" class="action-btn view-btn" title="View Event">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>&admin=1" class="action-btn edit-btn" title="Edit Event">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $event['id']; ?>, '<?php echo addslashes($event['name']); ?>')" class="action-btn delete-btn" title="Delete Event">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-confirm-modal">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p id="deleteMessage">Are you sure you want to delete this event?</p>
            <div class="modal-actions">
                <a href="#" id="confirmDelete" class="action-btn delete-btn">
                    <i class="fas fa-trash"></i> Delete
                </a>
                <button onclick="closeModal()" class="action-btn view-btn">
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
            confirmBtn.href = `admin_events.php?delete=${eventId}`;
            
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