<?php
require_once 'db.php';
require_once 'Users.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $userObj = new User($conn);

    try {
        $userObj->deleteUser($userId);
        header('Location: admin.php');
        exit;
    } catch (Exception $e) {
        echo "Error deleting user: " . $e->getMessage();
    }
}
?>