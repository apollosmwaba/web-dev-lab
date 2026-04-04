<?php
$appName = 'Student Suggestion Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Track') ?> - <?= $appName ?></title>
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
                <li><a href="/student-suggestions/public/index.php?route=student/viewAll">Previous</a></li>
                <li><a href="/student-suggestions/public/index.php?route=admin/login" class="btn-admin">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="track-page">
                <h1 class="page-title">Track Your Submission</h1>
                <p class="page-subtitle">Enter your tracking code to check the status of your suggestion</p>

                <form class="track-form" action="/student-suggestions/public/index.php?route=student/track" method="GET">
                    <div class="track-input-group">
                        <input type="text" name="ticket" id="ticket" 
                               placeholder="Enter tracking code (e.g., SUG-ABC123-4567)"
                               value="<?= htmlspecialchars($ticketCode) ?>" required>
                        <button type="submit" class="btn btn-primary">Track</button>
                    </div>
                </form>

                <?php if ($suggestion): ?>
                <div class="tracking-result">
                    <div class="result-header">
                        <h2><?= htmlspecialchars($suggestion['title']) ?></h2>
                        <span class="status-badge status-<?= $suggestion['status'] ?>"><?= ucfirst($suggestion['status']) ?></span>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item <?= in_array($suggestion['status'], ['new', 'in_review', 'resolved', 'actioned']) ? 'active' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>Submitted</h4>
                                <p><?= date('M d, Y H:i', strtotime($suggestion['created_at'])) ?></p>
                            </div>
                        </div>
                        <div class="timeline-item <?= in_array($suggestion['status'], ['in_review', 'resolved', 'actioned']) ? 'active' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>In Review</h4>
                                <p>Under consideration by our team</p>
                            </div>
                        </div>
                        <div class="timeline-item <?= in_array($suggestion['status'], ['resolved', 'actioned']) ? 'active' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>Resolved</h4>
                                <p><?= $suggestion['resolved_at'] ? 'Completed on ' . date('M d, Y', strtotime($suggestion['resolved_at'])) : 'Pending' ?></p>
                            </div>
                        </div>
                        <div class="timeline-item <?= $suggestion['status'] === 'actioned' ? 'active' : '' ?>">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>Actioned</h4>
                                <p>Implementation completed</p>
                            </div>
                        </div>
                    </div>

                    <div class="suggestion-details">
                        <h3>Details</h3>
                        <table class="details-table">
                            <tr>
                                <th>Ticket Code:</th>
                                <td><?= htmlspecialchars($suggestion['ticket_code']) ?></td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td><?= htmlspecialchars($suggestion['category'] ?? 'Not specified') ?></td>
                            </tr>
                            <tr>
                                <th>Urgency:</th>
                                <td><span class="urgency-badge urgency-<?= $suggestion['urgency_level'] ?>"><?= ucfirst($suggestion['urgency_level']) ?></span></td>
                            </tr>
                            <?php if ($suggestion['resolved_by_name']): ?>
                            <tr>
                                <th>Resolved By:</th>
                                <td><?= htmlspecialchars($suggestion['resolved_by_name']) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>

                    <?php if (!empty($responses)): ?>
                    <div class="responses-section">
                        <h3>Admin Responses</h3>
                        <?php foreach ($responses as $response): ?>
                        <div class="response-card <?= $response['is_internal_note'] ? 'internal-note' : '' ?>">
                            <div class="response-header">
                                <span class="response-author">
                                    <?= $response['is_anonymous'] ? 'Anonymous Admin' : htmlspecialchars($response['full_name'] ?? 'Admin') ?>
                                </span>
                                <span class="response-date"><?= date('M d, Y H:i', strtotime($response['created_at'])) ?></span>
                            </div>
                            <div class="response-text">
                                <?= nl2br(htmlspecialchars($response['response_text'])) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php elseif ($ticketCode): ?>
                <div class="alert alert-error">
                    No suggestion found with ticket code: <strong><?= htmlspecialchars($ticketCode) ?></strong>
                    <br>Please check your tracking code and try again.
                </div>
                <?php endif; ?>
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
