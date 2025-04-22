<?php 
session_start();
include 'header.php';
include 'database.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["name"]);
    $password = $_POST["password"];
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to homepage or dashboard
            header("Location: homepage.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "User not found!";
    }
    
    $stmt->close();
}
?>

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
            .error {
                color: red;
                margin-bottom: 10px;
            }
        </style>
    </head>
<body>
    
<div style="text-align: center; margin: 20px 0;">
    <h2>Login to Your Account</h2>
    <?php if(isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
</div>

<form method="POST" action="login.php">
    <i class="fa fa-user"></i>
    <input type="text" name="name" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Sign in</button>
    <p style="margin-top: 15px;">Don't have an account? <a href="registration.php">Register here</a>.</p>
</form>

</body>
</html>
<?php include 'footer.php'; ?>