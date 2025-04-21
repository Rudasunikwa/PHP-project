<?php
// Event Features Array
$event_features = [
    "Live Music Concert",
    "Conference",
    "Live watching Movies",
    "Science Workshops",
    "Training Sessions",
    "Discounts on clothes"
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Features</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .features-container {
            width: 50%;
            margin: auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: blue;
            color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="features-container">
        <h2>Event Features</h2>
        <ul>
            <?php foreach ($event_features as $feature) {
                echo "<li>$feature</li>";
            } ?>
        </ul>
    </div>

</body>
</html>
