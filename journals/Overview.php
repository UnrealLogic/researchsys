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
   
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="admin.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="main-content">
            <h2>Reports</h2>
            <button id="summaryButton" onclick="showReport('summary')">Summary Report</button>
            <button id="articlesButton" onclick="showReport('articles')">Articles</button>
            <button id="conferencesButton" onclick="showReport('conferences')">Conferences</button>
            <button id="booksButton" onclick="showReport('books')">Books</button>

            <div id="summary" class="report-section">
                <h3>Summary Report</h3>
                <p>Total Users: <?php echo $totalUsers; ?></p>
                <p>Total Articles: <?php echo $totalArticles; ?></p>
                <p>Total Conferences: <?php echo $totalConferences; ?></p>
                <p>Total Books: <?php echo $totalBooks; ?></p>
                <canvas id="submissionsChart" width="400" height="200"></canvas>

             
            </div>

            <div id="articles" class="report-section">
                <h3>Articles by School</h3>
                <select id="schoolFilterArticles" onchange="filterTable('articlesTable', this.value)">
                    <option value="">All Schools</option>
                    <?php foreach ($schools as $schoolName => $submissions): ?>
                        <option value="<?php echo $schoolName; ?>"><?php echo $schoolName; ?></option>
                    <?php endforeach; ?>
                </select>
                <table id="articlesTable" class="display">
                    <thead>
                        <tr>
                            <th>Journal</th>
                            <th>Author</th>
    
                            <th>Article</th>
                            <th>Publication Date</th>
                            <th>School</th>
                            <th>ISSN Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($article['journal']); ?></td>
                                <td><?php echo htmlspecialchars($userObj->getUserById($article['user_id'])['username']); ?></td>
                                <td><?php echo htmlspecialchars($article['article']); ?></td>
                                <td><?php echo htmlspecialchars($article['publication_date']); ?></td>
                                <td><?php echo htmlspecialchars($article['school']); ?></td>
                                <td><?php echo htmlspecialchars($article['ISSN_number']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="conferences" class="report-section">
                <h3>Conferences by School</h3>
                <select id="schoolFilterConferences" onchange="filterTable('conferencesTable', this.value)">
                    <option value="">All Schools</option>
                    <?php foreach ($schools as $schoolName => $submissions): ?>
                        <option value="<?php echo $schoolName; ?>"><?php echo $schoolName; ?></option>
                    <?php endforeach; ?>
                </select>
                <table id="conferencesTable" class="display">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conferences as $conference): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($conference['title']); ?></td>
                                <td><?php echo htmlspecialchars($userObj->getUserById($conference['user_id'])['username']); ?></td>
                                <td><?php echo htmlspecialchars($conference['description']); ?></td>
                                <td><?php echo htmlspecialchars($conference['date']); ?></td>
                                <td><?php echo htmlspecialchars($conference['location']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="books" class="report-section">
                <h3>Books by School</h3>
                <select id="schoolFilterBooks" onchange="filterTable('booksTable', this.value)">
                    <option value="">All Schools</option>
                    <?php foreach ($schools as $schoolName => $submissions): ?>
                        <option value="<?php echo $schoolName; ?>"><?php echo $schoolName; ?></option>
                    <?php endforeach; ?>
                </select>
                <table id="booksTable" class="display">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                           
                            <th>Publication Date</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($Books as $Book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($Book['title']); ?></td>
                                <td><?php echo htmlspecialchars($userObj->getUserById($Book['user_id'])['username']); ?></td>
                                
                                <td><?php echo htmlspecialchars($Book['publication_date']); ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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

        // Initialize DataTables
        $('#articlesTable').DataTable();
        $('#conferencesTable').DataTable();
        $('#booksTable').DataTable();

        // Show the summary report by default
        showReport('summary');
    });

    function showReport(report) {
        var sections = document.querySelectorAll('.report-section');
        sections.forEach(function(section) {
            section.classList.remove('active');
        });

        var activeSection = document.getElementById(report);
        if (activeSection) {
            activeSection.classList.add('active');
        }
    }

    function filterTable(tableId, filterValue, ColumnIndex) {
        var table = $('#' + tableId).DataTable();
        table.column(columnIndex).search(filterValue).draw(); // Assuming the 6th column is for the school
    }
    </script>
</body>
</html>

