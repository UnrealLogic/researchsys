<?php
require_once 'db.php';
require_once 'Users.php';
session_start();


$error = "";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userObj = new User($conn);

    try {
        $user = $userObj->login($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];  // Store the role in the session
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="CSS/style.css">
<head>
    <title>Login</title>
       
<body>
    <div class="login-container">
        <img src="img/zcas-u-PNG-1004x1024.png" alt="ZCASU" class="center" href="login.php"><br>
        <div class = "login-header">
    <h1>Login</h1>
            
            <div class = "login-form">
    
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="post">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" id = "password" required><br>
        <button type="submit">Login</button>
    </form>
                <div class="signup-link">
        Don't have an account? <a href="signup.php">Sign Up</a>
    </div>
            </div>
        </div>
    </div>
</body>
</html>