<?php
require_once 'db.php';
require_once 'conference.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $location = trim ($_POST ['location']);
    $userId = $_SESSION['user_id'];

    if (empty($title) || empty($description) || empty($date)) {
        $error = "All fields are required.";
    } else {
        try {
            $conference = new Conference($conn);
            if ($conference->submitConference($userId, $title, $description, $date,$location)) {
                echo "Conference submitted successfully!";
            } else {
                $error = "Failed to submit conference.";
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
    <title>Submit Conference</title>
        <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="header">
        <h1>Conference Submission </h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="index.php">My Dashboard</a>
            <a href="submit_article.php">Submit New Article</a>
            <a href="submit_conference.php">Submit New Conference</a>
            <a href="submit_book.php">Submit New Book</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        Title: <input type="text" name="title" required><br>
        Description: <textarea name="description" required></textarea><br>
        Date: <input type="date" name="date" required><br>
        Location: <input type="text" name="location" required><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
