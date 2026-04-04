<?php
$appName = 'Student Suggestion Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Confirmation') ?> - <?= $appName ?></title>
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
                <li><a href="/student-suggestions/public/index.php?route=student/track" class="active">Track</a></li>
                <li><a href="/student-suggestions/public/index.php?route=admin/login" class="btn-admin">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="confirmation-page">
                <div class="success-icon">✓</div>
                <h1 class="page-title">Submission Confirmed!</h1>
                <p class="confirmation-message">Your suggestion has been successfully submitted.</p>
                
                <div class="ticket-card">
                    <h2>Your Tracking Code</h2>
                    <div class="ticket-code"><?= htmlspecialchars($ticketCode) ?></div>
                    <p class="ticket-info">Save this code to track the status of your submission</p>
                </div>

                <div class="suggestion-summary">
                    <h3>Submission Summary</h3>
                    <table class="summary-table">
                        <tr>
                            <th>Title:</th>
                            <td><?= htmlspecialchars($suggestion['title']) ?></td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td><?= htmlspecialchars($suggestion['category'] ?? 'Not specified') ?></td>
                        </tr>
                        <tr>
                            <th>Urgency:</th>
                            <td><span class="urgency-badge urgency-<?= $suggestion['urgency_level'] ?>"><?= ucfirst($suggestion['urgency_level']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="status-badge status-<?= $suggestion['status'] ?>"><?= ucfirst($suggestion['status']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Submitted:</th>
                            <td><?= date('M d, Y H:i', strtotime($suggestion['created_at'])) ?></td>
                        </tr>
                        <?php if ($suggestion['file_original_name']): ?>
                        <tr>
                            <th>Attachment:</th>
                            <td>
                                <a href="/student-suggestions/public/<?= htmlspecialchars($suggestion['file_path']) ?>" target="_blank" class="file-link">
                                    📎 <?= htmlspecialchars($suggestion['file_original_name']) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>

                <div class="confirmation-actions">
                    <a href="/student-suggestions/public/index.php?route=student/track&ticket=<?= urlencode($ticketCode) ?>" class="btn btn-primary">Track Status</a>
                    <a href="/student-suggestions/public/index.php?route=student/submit" class="btn btn-secondary">Submit Another</a>
                    <a href="/student-suggestions/public/index.php" class="btn btn-outline">Back to Home</a>
                </div>

                <div class="alert alert-info">
                    <strong>Important:</strong> Please save your tracking code. This is the only way to check the status of your anonymous submission.
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= $appName ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="/student-suggestions/public/assets/js/main.js"></script>
</body>
</html>
