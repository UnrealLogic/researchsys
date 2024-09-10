<?php
require_once 'db.php';
require_once 'Book.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $publicationDate = trim($_POST['publication_date']);
    $userId = $_SESSION['user_id'];

    if (empty($title) || empty($description) || empty($publicationDate)) {
        $error = "All fields are required.";
    } else {
        try {
            $book = new Book($conn);
            if ($book->submitBook($userId, $title, $description, $publicationDate)) {
                echo "Book submitted successfully!";
            } else {
                $error = "Failed to submit book.";
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
    <title>Submit Book</title>
        <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="header">
        <h1>Book Submission </h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="index.php">Dashboard</a>
            <a href="submit_article.php">Submit New Article</a>
            <a href="submit_conference.php">Submit New Conference</a>
            <a href="submit_book.php">Submit New Book</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class = "main-content">
    <h2>Submit Book</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        Title: <input type="text" name="title" required><br>
        Description: <textarea name="description" required></textarea><br>
        Publication Date: <input type="date" name="publication_date" required><br>
        <button type="submit">Submit</button>
    </form>
        </div>
</body>
</html>
