<?php
require_once 'db.php';
require_once 'Book.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$bookObj = new Book($conn);

if (isset($_GET['id'])) {
    $book = $bookObj->getBookById($_GET['id']);
    if ($book['user_id'] !== $_SESSION['user_id']) {
        header('Location: index.php');
        exit;
    }
}

if (isset($_POST['update'])) {
    $bookId = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publicationDate = $_POST['publication_date'];
    

    try {
        $bookObj->updateBook($bookId, $title, $author, $publicationDate);
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
    <title>Edit Book</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="header">
        <h1>Edit Book</h1>
    </div>
    <div class="main-content">
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['id']); ?>">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            <label for="author">Author:</label>
            <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            <label for="publication_date">Publication Date:</label>
            <input type="date" name="publication_date" id="publication_date" value="<?php echo htmlspecialchars($book['publication_date']); ?>" required>
            <button type="submit" name="update">Update Book</button>
        </form>
    </div>
</body>
</html>