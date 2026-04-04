<?php
$appName = 'Student Suggestion Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Home') ?> - <?= $appName ?></title>
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
                <li><a href="/student-suggestions/public/index.php?route=student/index" class="active">Home</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/submit">Submit</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/track">Track</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/viewAll">Previous</a></li>
                <li><a href="/student-suggestions/public/index.php?route=admin/login" class="btn-admin">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="hero-section">
            <div class="container">
                <h1 class="hero-title">Make Your Voice Heard</h1>
                <p class="hero-subtitle">Submit suggestions, report issues, and help improve our campus community</p>
                <div class="hero-actions">
                    <a href="/student-suggestions/public/index.php?route=student/submit" class="btn btn-primary btn-lg">Submit Suggestion</a>
                    <a href="/student-suggestions/public/index.php?route=student/track" class="btn btn-secondary btn-lg">Track Status</a>
                </div>
            </div>
        </div>

        <div class="features-section">
            <div class="container">
                <h2 class="section-title">How It Works</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📝</div>
                        <h3>Submit Anonymously</h3>
                        <p>Share your ideas without revealing your identity. Optional category tags help organize submissions.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📎</div>
                        <h3>Attach Evidence</h3>
                        <p>Upload screenshots, photos, or documents to provide context for your suggestion.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎫</div>
                        <h3>Get Tracking Code</h3>
                        <p>Receive a unique ticket code to track the status of your submission anytime.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>Set Priority</h3>
                        <p>Mark urgency levels (Low/Medium/High) to flag critical issues that need attention.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($recentSuggestions)): ?>
        <div class="recent-section">
            <div class="container">
                <h2 class="section-title">Recently Resolved Suggestions</h2>
                <div class="suggestions-list">
                    <?php foreach ($recentSuggestions as $suggestion): ?>
                    <div class="suggestion-card">
                        <div class="suggestion-header">
                            <span class="ticket-badge"><?= htmlspecialchars($suggestion['ticket_code']) ?></span>
                            <span class="status-badge status-<?= $suggestion['status'] ?>"><?= ucfirst($suggestion['status']) ?></span>
                        </div>
                        <h3 class="suggestion-title"><?= htmlspecialchars($suggestion['title']) ?></h3>
                        <?php if ($suggestion['category']): ?>
                        <span class="category-tag"><?= htmlspecialchars($suggestion['category']) ?></span>
                        <?php endif; ?>
                        <span class="urgency-badge urgency-<?= $suggestion['urgency_level'] ?>"><?= ucfirst($suggestion['urgency_level']) ?></span>
                        <p class="suggestion-date"><?= date('M d, Y', strtotime($suggestion['created_at'])) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="/student-suggestions/public/index.php?route=student/viewAll" class="btn btn-outline">View All Suggestions</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
