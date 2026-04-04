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
    <title><?= htmlspecialchars($pageTitle ?? 'All Suggestions') ?> - <?= $appName ?></title>
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
                <a href="/student-suggestions/public/index.php?route=admin/dashboard" class="nav-item">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="/student-suggestions/public/index.php?route=admin/suggestions" class="nav-item active">
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
                <h1 class="page-title">All Suggestions</h1>
                <div class="header-actions">
                    <a href="/student-suggestions/public/index.php?route=admin/export" class="btn btn-outline btn-sm">📥 Export CSV</a>
                </div>
            </header>

            <div class="content-section">
                <div class="filters-bar">
                    <form method="GET" class="filters-form">
                        <input type="hidden" name="route" value="admin/suggestions">
                        <div class="filter-group">
                            <select name="status" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="new" <?= $currentStatus === 'new' ? 'selected' : '' ?>>New</option>
                                <option value="in_review" <?= $currentStatus === 'in_review' ? 'selected' : '' ?>>In Review</option>
                                <option value="resolved" <?= $currentStatus === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                <option value="actioned" <?= $currentStatus === 'actioned' ? 'selected' : '' ?>>Actioned</option>
                            </select>
                            <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if ($currentStatus || $search): ?>
                            <a href="/student-suggestions/public/index.php?route=admin/suggestions" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>
                    
                    <div class="bulk-actions">
                        <label>Bulk Action:</label>
                        <select id="bulkStatus">
                            <option value="">Select...</option>
                            <option value="in_review">Mark In Review</option>
                            <option value="resolved">Mark Resolved</option>
                            <option value="actioned">Mark Actioned</option>
                        </select>
                        <button class="btn btn-primary" onclick="applyBulkAction()">Apply</button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                                <th>Ticket</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Urgency</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($suggestions)): ?>
                                <?php foreach ($suggestions as $suggestion): ?>
                                <tr>
                                    <td><input type="checkbox" class="item-checkbox" value="<?= $suggestion['id'] ?>"></td>
                                    <td><span class="ticket-code-small"><?= htmlspecialchars($suggestion['ticket_code']) ?></span></td>
                                    <td><?= htmlspecialchars($suggestion['title']) ?></td>
                                    <td><?= htmlspecialchars($suggestion['category'] ?? '-') ?></td>
                                    <td><span class="urgency-badge urgency-<?= $suggestion['urgency_level'] ?>"><?= ucfirst($suggestion['urgency_level']) ?></span></td>
                                    <td><span class="status-badge status-<?= $suggestion['status'] ?>"><?= ucfirst($suggestion['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($suggestion['created_at'])) ?></td>
                                    <td>
                                        <a href="/student-suggestions/public/index.php?route=admin/viewSuggestion&=<?= $suggestion['id'] ?>" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No suggestions found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
        }

        function applyBulkAction() {
            const status = document.getElementById('bulkStatus').value;
            if (!status) {
                alert('Please select a status');
                return;
            }

            const selectedIds = [];
            document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                selectedIds.push(cb.value);
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one item');
                return;
            }

            const formData = new FormData();
            formData.append('ids', selectedIds);
            formData.append('status', status);

            fetch('/student-suggestions/public/index.php?route=admin/bulkUpdate', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Error applying bulk action'));
        }
    </script>
</body>
</html>
