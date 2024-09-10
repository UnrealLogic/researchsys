<?php
require_once 'db.php';
require_once 'Articles.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $journal = trim($_POST['journal']);
    $article = trim($_POST['article']);
    $publicationDate = trim($_POST['publication_date']);
    $school = trim($_POST['school']);
    $ISSNNumber = trim($_POST['ISSN_number']);

    if (empty($journal) || empty($article) || empty($publicationDate) || empty($school) || empty($ISSNNumber)) {
        $error = "All fields are required.";
    } else {
        try {
            $articleObj = new Article($conn);
            $userId = $_SESSION['user_id'];
            $articleObj->submitArticle($userId, $journal, $article, $publicationDate, $school, $ISSNNumber);
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Journal Article</title>
       <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
      <div class="header">
        <h1>Journal Submission Platform</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="index.php">Dashboard</a>
            <a href="submit_article.php">Submit New Article</a>
            <a href="submit_conference.php">Submit New Conference</a>
            <a href="submit_book.php">Submit New Book</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="main-content">
            <h2>Submit New Article</h2>
            <?php if ($error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post">
                Journal: <input type="text" name="journal" required><br>
                Article: <input type="text" name="article" required><br>
                Publication Date: <input type="date" name="publication_date" required><br>
                School: <input type="text" name="school" required><br>
                ISSN Number: <input type="text" name="ISSN_number" required><br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>