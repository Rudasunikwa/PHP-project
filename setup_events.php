<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create or alter events table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE,
    event_time TIME,
    location VARCHAR(255),
    price DECIMAL(10, 2),
    capacity INT,
    image_path VARCHAR(255),
    event_category VARCHAR(50),
    organizer_id INT(11),
    organizer_name VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Events table created successfully or already exists<br>";
} else {
    echo "Error creating events table: " . $conn->error . "<br>";
}

// Add missing columns if they don't exist
$columns_to_add = [
    "event_category VARCHAR(50)",
    "organizer_id INT(11)",
    "organizer_name VARCHAR(255)",
    "contact_email VARCHAR(255)",
    "contact_phone VARCHAR(50)",
    "status VARCHAR(20) DEFAULT 'pending'"
];

foreach ($columns_to_add as $column_def) {
    $column_name = explode(' ', $column_def)[0];
    $check_column = $conn->query("SHOW COLUMNS FROM `events` LIKE '$column_name'");
    
    if ($check_column->num_rows == 0) {
        $alter_sql = "ALTER TABLE `events` ADD COLUMN $column_def";
        if ($conn->query($alter_sql) === TRUE) {
            echo "Added missing column $column_name to events table<br>";
        } else {
            echo "Error adding column $column_name: " . $conn->error . "<br>";
        }
    }
}

// Create bookings table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    event_id INT(11) NOT NULL,
    event_date DATE,
    num_tickets INT NOT NULL,
    total_amount DECIMAL(10, 2),
    payment_status VARCHAR(50) DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Bookings table created successfully or already exists<br>";
} else {
    echo "Error creating bookings table: " . $conn->error . "<br>";
}

// Insert sample events if the events table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM events");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $events = [
        [
            'name' => 'Concert in the Park',
            'description' => 'Enjoy a night of live music under the stars in our beautiful city park.',
            'event_date' => '2025-07-15',
            'event_time' => '19:00:00',
            'location' => 'City Park Amphitheater',
            'price' => 25.00,
            'capacity' => 500,
            'image_path' => 'music.png',
            'event_category' => 'music',
            'organizer_id' => 1,
            'organizer_name' => 'City Events',
            'contact_email' => 'events@cityevents.com',
            'contact_phone' => '555-1234',
            'status' => 'approved'
        ],
        [
            'name' => 'Art Exhibition',
            'description' => 'Discover new artists and admire breathtaking artwork from around the world.',
            'event_date' => '2025-05-22',
            'event_time' => '14:00:00',
            'location' => 'Downtown Art Gallery',
            'price' => 15.00,
            'capacity' => 200,
            'image_path' => 'art.png',
            'event_category' => 'other',
            'organizer_id' => 1,
            'organizer_name' => 'Art Guild',
            'contact_email' => 'gallery@artguild.com',
            'contact_phone' => '555-2345',
            'status' => 'approved'
        ],
        [
            'name' => 'Comedy Show',
            'description' => 'Laugh out loud with our lineup of talented comedians for a night of fun.',
            'event_date' => '2025-06-29',
            'event_time' => '20:00:00',
            'location' => 'Laugh Factory',
            'price' => 30.00,
            'capacity' => 300,
            'image_path' => 'comedy.png',
            'event_category' => 'other',
            'organizer_id' => 1,
            'organizer_name' => 'Laugh Inc',
            'contact_email' => 'info@laughinc.com',
            'contact_phone' => '555-3456',
            'status' => 'approved'
        ],
        [
            'name' => 'Wedding Expo',
            'description' => 'Plan your perfect wedding day with vendors, samples, and great ideas all in one place.',
            'event_date' => '2025-08-10',
            'event_time' => '12:00:00',
            'location' => 'Grand Convention Center',
            'price' => 10.00,
            'capacity' => 1000,
            'image_path' => 'wedding.png',
            'event_category' => 'wedding',
            'organizer_id' => 1,
            'organizer_name' => 'Wedding Planners',
            'contact_email' => 'info@weddingexpo.com',
            'contact_phone' => '555-4567',
            'status' => 'approved'
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO events (name, description, event_date, event_time, location, price, capacity, image_path, event_category, organizer_id, organizer_name, contact_email, contact_phone, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($events as $event) {
        $stmt->bind_param("ssssssississss", 
            $event['name'], 
            $event['description'], 
            $event['event_date'], 
            $event['event_time'], 
            $event['location'], 
            $event['price'], 
            $event['capacity'], 
            $event['image_path'],
            $event['event_category'],
            $event['organizer_id'],
            $event['organizer_name'],
            $event['contact_email'],
            $event['contact_phone'],
            $event['status']
        );
        $stmt->execute();
    }
    
    echo "Sample events added to database<br>";
    $stmt->close();
}

echo "Database setup completed. <a href='feature.php'>View Events</a> | <a href='userinfo.php'>Go to My Account</a>";

$conn->close();
?>