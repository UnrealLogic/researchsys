<?php
require_once 'db.php';
require_once 'Articles.php';
require_once 'conference.php';
require_once 'Book.php';
// Debugging


session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
//var_dump($_SESSION);


$articleObj = new Article($conn);
$conferenceObj = new conference ($conn);
$BookObj = new Book ($conn);

$userId = $_SESSION['user_id'];
$articles = $articleObj->getArticlesByUser($userId);
$conference = $conferenceObj->getconferencesByUser($userId);
$Book = $BookObj->getBookByUser($userId);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
       <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
  <div class="header">
        <h1>Journal Submission Platform</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="index.php">My Dashboard</a>
            <a href="admin.php"> Admin</a>
            <a href="submit_article.php">Submit New Article</a>
            <a href="submit_conference.php">Submit New Conference</a>
            <a href="submit_book.php">Submit New Book</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="main-content">
            <h2>My Articles</h2>
            <?php foreach ($articles as $article): ?>
                <h3><?php echo htmlspecialchars($article['journal']); ?></h3>
                <p>Article: <?php echo htmlspecialchars($article['article']); ?></p>
                <p>Publication Date: <?php echo htmlspecialchars($article['publication_date']); ?></p>
                <p>School: <?php echo htmlspecialchars($article['school']); ?></p>
                <p>ISSN Number: <?php echo htmlspecialchars($article['ISSN_number']); ?></p>
            <a href="edit_article.php?id=<?php echo $article['id']; ?>">Edit</a>
                
                <hr>
            <?php endforeach; ?>
            
            
            <h2>Your Conferences</h2>
            <?php foreach ($conference as $conference): ?>
                <h3><?php echo htmlspecialchars($conference['title']); ?></h3>
                <p><?php echo htmlspecialchars($conference['description']); ?></p>
                <p>Date: <?php echo htmlspecialchars($conference['date']); ?></p>
                <p>Location: <?php echo htmlspecialchars($conference['location']); ?></p>
            <a href="edit_conference.php?id=<?php echo $conference['id']; ?>">Edit</a>
                <hr>
            <?php endforeach; ?>
            
            <h2>Your Books</h2>
            <?php foreach ($Book as $Book): ?>
                <h3><?php echo htmlspecialchars($Book['title']); ?></h3>
                <p><?php echo htmlspecialchars($Book['description']); ?></p>
                <p>Date: <?php echo htmlspecialchars($Book['publication_date']); ?></p>
            <a href="edit_book.php?id=<?php echo $book['id']; ?>">Edit</a>
                <hr>
            <?php endforeach; ?>
            
            
        </div>
    </div>
</body>
</html>