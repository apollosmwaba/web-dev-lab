<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Student Suggestion Portal') ?> - <?= $appName ?></title>
    <link rel="stylesheet" href="/student-suggestions/public/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="/student-suggestions/public/index.php" class="logo">
                <span class="logo-icon">📢</span>
                <span class="logo-text">Student Voice</span>
            </a>
            <ul class="nav-links">
                <li><a href="/student-suggestions/public/index.php?route=student/index">Home</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/submit">Submit</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/track">Track</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/viewAll">Previous</a></li>
                <li><a href="/student-suggestions/public/index.php?route=admin/login" class="btn-admin">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= $appName ?>. All rights reserved.</p>
            <p>Your voice matters. Help us improve!</p>
        </div>
    </footer>

    <script src="/student-suggestions/public/assets/js/main.js"></script>
</body>
</html>
