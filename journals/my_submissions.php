<?php
require_once 'db.php';
require_once 'Articles.php';
require_once 'Conference.php';
require_once 'Book.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$article = new Article($conn);
$conference = new Conference($conn);
$book = new Book($conn);

$articles = $article->getArticlesByUser($userId);
$conferences = $conference->getConferencesByUser($userId);
$books = $book->getBooksByUser($userId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
</head>
<body>
    <h2>My Submissions</h2>
    <h3>Articles</h3>
    <?php foreach ($articles as $article): ?>
        <h4><?php echo htmlspecialchars($article['title']); ?></h4>
        <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
        <hr>
    <?php endforeach; ?>

    <h3>Conferences</h3>
    <?php foreach ($conferences as $conference): ?>
        <h4><?php echo htmlspecialchars($conference['title']); ?></h4>
        <p><?php echo nl2br(htmlspecialchars($conference['description'])); ?></p>
        <p>Date: <?php echo htmlspecialchars($conference['date']); ?></p>
        <hr>
    <?php endforeach; ?>

    <h3>Books</h3>
    <?php foreach ($books as $book): ?>
        <h4><?php echo htmlspecialchars($book['title']); ?></h4>
        <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
        <p>Publication Date: <?php echo htmlspecialchars($book['publication_date']); ?></p>
        <hr>
    <?php endforeach; ?>

    <a href="submit_article.php">Submit New Article</a><br>
    <a href="submit_conference.php">Submit New Conference</a><br>
    <a href="submit_book.php">Submit New Book</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
