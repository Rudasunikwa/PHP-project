<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "user_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT id, created_at FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id, $created_at);
$stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>User ID: <?php echo $id; ?></p>
    <p>Account created on: <?php echo $created_at; ?></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>