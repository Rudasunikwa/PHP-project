<?php include 'header.php'; ?>

<html>
    <head>
    <style>
        /* body {
                display: flex;
                justify-content: center; 
                align-items: center;     
                min-height: 100vh;      
                margin: 0;      
            } */
            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 15px 32px;
                width: 300px;
                margin: 0 auto;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            button{
                background-color: #4CAF50;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                justify-content: center;
            }
        </style>
    </head>
<body>
    

<form method="POST" action="booking.php">
    <i class="fa fa-user"></i>
    <input type="text" name="name" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Sign in</button>
</form>

<!-- <p>Don't have an account? <a href="signup.php">Create one here</a>.</p>

<p>Or you can sign in with <a href="google_signin.php">Google</a>.</p> -->

<?php 
if(isset($_POST["name"])){
    $name = htmlspecialchars($_POST["name"]); // Clean input for safety
    $password = htmlspecialchars($_POST["password"]); // Clean input for safety

    echo "<p>Name is: $name</p>";
    echo "<p>Password is: $password</p>";
}
?>

</body>
</html>
<!-- <?php include 'footer.php'; ?> -->















<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli("localhost", "root", "", "user_registration");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = htmlspecialchars($_POST["username"]);
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashed_password);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                header('Location: user_info.php');
                exit();
            } else {
                echo "<p>Incorrect password!</p>";
            }
        } else {
            echo "<p>No user found with that username!</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html> -->