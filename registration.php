<?php 
session_start();
include 'header.php';
include 'database.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

// Process registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST["email"]);
    
    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error_message = "Username already exists! Please choose another one.";
    } else {
        // Prepare SQL statement to insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);
        
        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            $_SESSION['registration_success'] = true;
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Registration failed: " . $stmt->error;
        }
        
        $stmt->close();
    }
    $check_stmt->close();
}
?>

<html>
    <head>
    <style>
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px 32px;
            width: 300px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
        }
        button {
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
        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }
    </style>
    </head>
<body>
    
<div style="text-align: center; margin: 20px 0;">
    <h2>Create an Account</h2>
    <?php if(isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
</div>

<form method="POST" action="registration.php">
    <i class="fa fa-user"></i>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email Address" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required 
           oninput="this.setCustomValidity(this.value != document.getElementsByName('password')[0].value ? 'Passwords do not match.' : '')"><br>
    <button type="submit">Register</button>
    <p style="margin-top: 15px;">Already have an account? <a href="login.php">Login here</a>.</p>
</form>

</body>
</html>
<?php include 'footer.php'; ?>