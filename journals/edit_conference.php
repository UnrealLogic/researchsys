<?php
require_once 'db.php';
require_once 'conference.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$conferenceObj = new conference($conn);

if (isset($_GET['id'])) {
    $conference = $conferenceObj->getconferenceById($_GET['id']);
    if ($conference['user_id'] !== $_SESSION['user_id']) {
        header('Location: index.php');
        exit;
    }
}

if (isset($_POST['update'])) {
    $conferenceId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    try {
        $conferenceObj->updateConference($conferenceId, $title, $description, $date, $location);
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
    <title>Edit Conference</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="header">
        <h1>Edit Conference</h1>
    </div>
    <div class="main-content">
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($conference['id']); ?>">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($conference['title']); ?>" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($conference['description']); ?></textarea>
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($conference['date']); ?>" required>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($conference['location']); ?>" required>
            <button type="submit" name="update">Update Conference</button>
        </form>
    </div>
</body>
</html>