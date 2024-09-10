<?php
require_once 'db.php';
require_once 'Articles.php';
require_once 'Users.php';
require_once 'conference.php';
require_once 'Book.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$articleObj = new Article($conn);
$userObj = new User($conn);
$conferenceObj = new conference($conn);
$BookObj = new Book($conn);

$articles = $articleObj->getAllArticles();
$users = $userObj->getAllUsers();
$conferences = $conferenceObj ->getAllConferences();
$Books = $BookObj ->getAllBooks();

$totalUsers = count($users);
$totalArticles = count($articles);
$totalConferences = count($conferences);
$totalBooks = count($Books);


$schools = ['School of Law' => ['article' => [], 'conference' => [], 'Books' => []],
    'School of Business' => ['article' => [], 'conference' => [], 'Books' => []],
    'School of Social Sciences' => ['article' => [], 'conference' => [], 'Books' => []],
    'School of Computing, Technology and Applied Sciences' => ['article' => [], 'conference' => [], 'Books' => []]
    
];

foreach ($articles as $article) {
    $user = $userObj->getUserById($article['user_id']);
    $schools[$user['school']]['article'][] = $article;
}

foreach ($conferences as $conference) {
    $user = $userObj->getUserById($conference['user_id']);
    $schools[$user['school']]['conference'][] = $conference;
}

foreach ($Books as $Book) {
    $user = $userObj->getUserById($Book['user_id']);
    $schools[$user['school']]['Books'][] = $Book;
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="admin.php">Admin Dashboard</a>
            <a href="Overview.php">Reports Overview</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="main-content">
            <h2>Summary Report</h2>
            <p>Total Users: <?php echo $totalUsers; ?></p>
            <p>Total Articles: <?php echo $totalArticles; ?></p>
            <p>Total Conferences: <?php echo $totalConferences; ?></p>
            <p>Total Books: <?php echo $totalBooks; ?></p>
            <canvas id="submissionsChart" width="400" height="200"></canvas>
            
            <h2>Users and Submissions</h2>
            
            <h3>Articles</h3>
            <?php foreach ($articles as $article): ?>
                <p>
                    Title: <?php echo htmlspecialchars($article['journal']); ?><br>
                    Author: <?php echo htmlspecialchars($userObj->getUserById($article['user_id'])['username']); ?><br>
                    Article: <?php echo htmlspecialchars($article['article']); ?><br>
                    Publication Date: <?php echo htmlspecialchars($article['publication_date']); ?><br>
                    School: <?php echo htmlspecialchars($article['school']); ?><br>
                    ISSN Number: <?php echo htmlspecialchars($article['ISSN_number']); ?><br>
                </p>
                <hr>
            <?php endforeach; ?>

            <h3>Conferences</h3>
            <?php foreach ($conferences as $conference): ?>
                <p>
                    Title: <?php echo htmlspecialchars($conference['title']); ?><br>
                    Author: <?php echo htmlspecialchars($userObj->getUserById($conference['user_id'])['username']); ?><br>
                    Description: <?php echo htmlspecialchars($conference['description']); ?><br>
                    Date: <?php echo htmlspecialchars($conference['date']); ?><br>
                    Location: <?php echo htmlspecialchars($conference['location']); ?><br>
                </p>
                <hr>
            <?php endforeach; ?>

            <h3>Books</h3>
            <?php foreach ($Books as $Book): ?>
                <p>
                    Title: <?php echo htmlspecialchars($Book['title']); ?><br>
                    Author: <?php echo htmlspecialchars($userObj->getUserById($Book['user_id'])['username']); ?><br>
                  
                    Publication Date: <?php echo htmlspecialchars($Book['publication_date']); ?><br>
                    
                </p>
                <hr>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var ctx = document.getElementById('submissionsChart').getContext('2d');
        var submissionsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Articles', 'Conferences', 'Books'],
                datasets: [{
                    label: 'Submissions',
                    data: [<?php echo $totalArticles; ?>, <?php echo $totalConferences; ?>, <?php echo $totalBooks; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>