<?php
include 'header.php';
include 'database.php';

// Check if we have the events table and if it has data
$has_events_table = false;
$events = [];

if (isset($conn)) {
    $has_events_table = $conn->query("SHOW TABLES LIKE 'events'")->num_rows > 0;
    
    if ($has_events_table) {
        $result = $conn->query("SELECT * FROM events ORDER BY event_date");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }
    }
}

// If we don't have event data from the database, use these fallback event features
if (empty($events)) {
    $event_features = [
        "Live Music Concert",
        "Conference",
        "Live watching Movies",
        "Science Workshops",
        "Training Sessions",
        "Discounts on clothes"
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <style>
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
        
        /* Event grid layout */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .event-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .event-image {
            height: 180px;
            background-color: #4CAF50;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5em;
            font-weight: bold;
        }
        
        .event-image.concert { background-color: #9C27B0; }
        .event-image.art { background-color: #2196F3; }
        .event-image.comedy { background-color: #FF9800; }
        .event-image.wedding { background-color: #E91E63; }
        .event-image.generic { background-color: #607D8B; }
        
        .event-content {
            padding: 20px;
        }
        
        .event-title {
            font-size: 1.5em;
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }
        
        .event-date {
            color: #666;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        .event-description {
            color: #555;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        
        .event-price {
            font-weight: bold;
            color: #4CAF50;
            font-size: 1.2em;
            margin-bottom: 15px;
        }
        
        .event-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        
        .event-btn:hover {
            background-color: #388E3C;
        }
        
        /* Simple features list styling (for fallback) */
        .features-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .features-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-item {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .feature-item:nth-child(2n) {
            background: linear-gradient(135deg, #2196F3, #0D47A1);
        }
        
        .feature-item:nth-child(3n) {
            background: linear-gradient(135deg, #FF9800, #E65100);
        }
    </style>
</head>
<body>
    <h1 class="page-title">Our Events</h1>
    <p class="subtitle">Discover and book amazing events happening near you</p>

    <?php if (!empty($events)): ?>
    <!-- Display events from database -->
    <div class="events-grid">
        <?php foreach ($events as $event): ?>
            <div class="event-card">
                <?php 
                    $img_class = 'generic';
                    if (stripos($event['name'], 'Concert') !== false) $img_class = 'concert';
                    elseif (stripos($event['name'], 'Art') !== false) $img_class = 'art';
                    elseif (stripos($event['name'], 'Comedy') !== false) $img_class = 'comedy';
                    elseif (stripos($event['name'], 'Wedding') !== false) $img_class = 'wedding';
                ?>
                <div class="event-image <?php echo $img_class; ?>">
                    <?php echo htmlspecialchars($event['name']); ?>
                </div>
                <div class="event-content">
                    <h3 class="event-title"><?php echo htmlspecialchars($event['name']); ?></h3>
                    <div class="event-date">
                        <?php echo date('F j, Y', strtotime($event['event_date'])); ?> at
                        <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                    </div>
                    <p class="event-description">
                        <?php echo htmlspecialchars($event['description']); ?>
                    </p>
                    <div class="event-price">$<?php echo number_format($event['price'], 2); ?></div>
                    <a href="booking.php" class="event-btn">Book Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php else: ?>
    <!-- Fallback to feature list -->
    <div class="features-container">
        <h2>Event Features</h2>
        <ul class="features-list">
            <?php foreach ($event_features as $index => $feature): ?>
                <li class="feature-item"><?php echo htmlspecialchars($feature); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</body>
</html>
<?php include 'footer.php'; ?>
