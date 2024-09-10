<?php
require_once 'db.php';
require_once 'Articles.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$articleObj = new Article($conn);

if (isset($_GET['id'])) {
    $article = $articleObj->getArticleById($_GET['id']);
    if ($article['user_id'] !== $_SESSION['user_id']) {
        header('Location: index.php');
        exit;
    }
}

if (isset($_POST['update'])) {
    $articleId = $_POST['id'];
    $journal = $_POST['journal'];
    $articleText = $_POST['article'];
    $publicationDate = $_POST['publication_date'];
    $school = $_POST['school'];
    $ISSN = $_POST['ISSN_number'];

    try {
        $articleObj->updateArticle($articleId, $journal, $articleText, $publicationDate, $school, $ISSN);
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="index.php">My Dashboard</a>
            <a href="submit_article.php">Submit New Article</a>
            <a href="submit_conference.php">Submit New Conference</a>
            <a href="submit_book.php">Submit New Book</a>
            <a href="logout.php">Logout</a>
        </div>
    <div class="header">
        <h1>Edit Article</h1>
    </div>
    <div class="main-content">
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
            <label for="journal">Journal:</label>
            <input type="text" name="journal" id="journal" value="<?php echo htmlspecialchars($article['journal']); ?>" required>
            <label for="article">Article:</label>
            <textarea name="article" id="article" required><?php echo htmlspecialchars($article['article']); ?></textarea>
            <label for="publication_date">Publication Date:</label>
            <input type="date" name="publication_date" id="publication_date" value="<?php echo htmlspecialchars($article['publication_date']); ?>" required>
            <label for="school">School:</label>
            <input type="text" name="school" id="school" value="<?php echo htmlspecialchars($article['school']); ?>" required>
            <label for="ISSN_number">ISSN Number:</label>
            <input type="text" name="ISSN_number" id="ISSN_number" value="<?php echo htmlspecialchars($article['ISSN_number']); ?>" required>
            <button type="submit" name="update">Update Article</button>
        </form>
    </div>
    </div>
</body>
</html>