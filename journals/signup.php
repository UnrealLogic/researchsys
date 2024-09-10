<?php
require_once 'db.php';
require_once 'Users.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $school = trim($_POST['school']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty ($school)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            $user = new User($conn);
            if ($user->signup($username, $email, $password,$school)) {
                header('Location: login.php');
                exit;
            } else {
                $error = "Signup failed.";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="signup-container">
        <img src="img/zcas-u-PNG-1004x1024.png" alt="ZCASU" class="center" href="login.php"><br>
    <h2>Signup</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        Username: <input type="text" name="username" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        Confirm Password: <input type="password" name="confirm_password" required><br>
        <label for="school">School:</label>
        <select id="school" name="school" required>
            <option value="School of Law">School of Law</option>
            <option value="School of Business">School of Business</option>
            <option value="School of Social Sciences">School of Social Sciences</option>
            <option value="School of Computing, Technology and Applied Sciences">School of Computing, Technology and Applied Sciences</option>
        </select><br>
        
        <button type="submit">Signup</button>
    </form>
    </div>
</body>
</html>