<?php include 'header.php'; ?>
<?php
$randomNumber = rand(10000, 99999); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Ticket Number</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin-top: 50px;
        }
        .ticket-box {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            background: blue;
            padding: 20px;
            display: inline-block;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <p>Keep Your Ticket Number:</p>
    <div class="ticket-box"><?php echo $randomNumber; ?></div>

</body>
</html>

<?php include 'footer.php'; ?>