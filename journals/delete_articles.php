<?php
require_once 'db.php';
require_once 'Articles.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['article_id'];
    $articleObj = new Article($conn);

    try {
        $articleObj->deleteArticle($articleId);
        header('Location: admin.php');
        exit;
    } catch (Exception $e) {
        echo "Error deleting article: " . $e->getMessage();
    }
}
?>