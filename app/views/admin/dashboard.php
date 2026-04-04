<?php
$appName = 'Student Suggestion Portal';
session_start();
$fullName = $_SESSION['full_name'] ?? 'Admin';
$userRole = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> - <?= $appName ?></title>
    <link rel="stylesheet" href="/student-suggestions/public/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <span class="logo-icon">📢</span>
                <span class="logo-text">Student Voice</span>
            </div>
            <nav class="sidebar-nav">
                <a href="/student-suggestions/public/index.php?route=admin/dashboard" class="nav-item active">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="/student-suggestions/public/index.php?route=admin/suggestions" class="nav-item">
                    <span class="nav-icon">📋</span> All Suggestions
                </a>
                <a href="/student-suggestions/public/index.php?route=admin/templates" class="nav-item">
                    <span class="nav-icon">📝</span> Templates
                </a>
                <a href="/student-suggestions/public/index.php" class="nav-item">
                    <span class="nav-icon">🏠</span> View Site
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <strong><?= htmlspecialchars($fullName) ?></strong>
                    <span class="role-badge"><?= ucfirst(str_replace('_', ' ', $userRole)) ?></span>
                </div>
                <a href="/student-suggestions/public/index.php?route=admin/logout" class="btn btn-outline btn-sm">Logout</a>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <h1 class="page-title">Dashboard</h1>
                <div class="header-actions">
                    <span class="welcome-text">Welcome back, <?= htmlspecialchars(explode(' ', $fullName)[0]) ?>!</span>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card stat-total">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= $stats['total'] ?? 0 ?></span>
                        <span class="stat-label">Total Submissions</span>
                    </div>
                </div>
                <div class="stat-card stat-new">
                    <div class="stat-icon">🆕</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= $stats['new_count'] ?? 0 ?></span>
                        <span class="stat-label">New</span>
                    </div>
                </div>
                <div class="stat-card stat-review">
                    <div class="stat-icon">👁️</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= $stats['in_review_count'] ?? 0 ?></span>
                        <span class="stat-label">In Review</span>
                    </div>
                </div>
                <div class="stat-card stat-resolved">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= ($stats['resolved_count'] ?? 0) + ($stats['actioned_count'] ?? 0) ?></span>
                        <span class="stat-label">Resolved</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Recent Submissions</h2>
                    <a href="/student-suggestions/public/index.php?route=admin/suggestions" class="btn btn-outline btn-sm">View All</a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Urgency</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentSuggestions)): ?>
                                <?php foreach ($recentSuggestions as $suggestion): ?>
                                <tr>
                                    <td><span class="ticket-code-small"><?= htmlspecialchars($suggestion['ticket_code']) ?></span></td>
                                    <td><?= htmlspecialchars($suggestion['title']) ?></td>
                                    <td><?= htmlspecialchars($suggestion['category'] ?? '-') ?></td>
                                    <td><span class="urgency-badge urgency-<?= $suggestion['urgency_level'] ?>"><?= ucfirst($suggestion['urgency_level']) ?></span></td>
                                    <td><span class="status-badge status-<?= $suggestion['status'] ?>"><?= ucfirst($suggestion['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($suggestion['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No suggestions yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
