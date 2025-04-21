<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Registration Form</h2>
    <form method="POST" action="registration.php">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli("localhost", "root", "", "user_registration");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = htmlspecialchars($_POST["username"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "<p>Registration successful!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>