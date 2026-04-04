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
    <title><?= htmlspecialchars($pageTitle ?? 'View Suggestion') ?> - <?= $appName ?></title>
    <link rel="stylesheet" href="/student-suggestions/public/assets/css/style.css">
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
                <h1 class="page-title">Suggestion #<?= $suggestion['id'] ?></h1>
                <div class="header-actions">
                    <a href="/student-suggestions/public/index.php?route=admin/suggestions" class="btn btn-secondary">← Back</a>
                </div>
            </header>

            <div class="suggestion-detail-grid">
                <div class="detail-section">
                    <h2>Suggestion Details</h2>
                    <table class="details-table">
                        <tr>
                            <th>Ticket Code:</th>
                            <td><span class="ticket-code-small"><?= htmlspecialchars($suggestion['ticket_code']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td><?= htmlspecialchars($suggestion['title']) ?></td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td><?= nl2br(htmlspecialchars($suggestion['description'])) ?></td>
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
                            <td>
                                <select id="statusSelect" onchange="updateStatus(<?= $suggestion['id'] ?>, this.value)">
                                    <option value="new" <?= $suggestion['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                    <option value="in_review" <?= $suggestion['status'] === 'in_review' ? 'selected' : '' ?>>In Review</option>
                                    <option value="resolved" <?= $suggestion['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                    <option value="actioned" <?= $suggestion['status'] === 'actioned' ? 'selected' : '' ?>>Actioned</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted:</th>
                            <td><?= date('M d, Y H:i', strtotime($suggestion['created_at'])) ?></td>
                        </tr>
                        <?php if ($suggestion['student_name']): ?>
                        <tr>
                            <th>Student:</th>
                            <td><?= htmlspecialchars($suggestion['student_name']) ?> (<?= htmlspecialchars($suggestion['student_email']) ?>)</td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <th>Student:</th>
                            <td><em>Anonymous Submission</em></td>
                        </tr>
                        <?php endif; ?>
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

                <div class="detail-section">
                    <h2>Add Response</h2>
                    <form id="responseForm" onsubmit="addResponse(event, <?= $suggestion['id'] ?>)">
                        <div class="form-group">
                            <label>Use Template:</label>
                            <select id="templateSelect" onchange="insertTemplate()">
                                <option value="">-- Select Template --</option>
                                <?php foreach ($templates as $template): ?>
                                <option value="<?= htmlspecialchars($template['template_content']) ?>">
                                    <?= htmlspecialchars($template['template_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="responseText">Your Response:</label>
                            <textarea id="responseText" rows="5" required placeholder="Type your response here..."></textarea>
                        </div>
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous">
                                <span>Post Anonymously</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_internal_note" id="is_internal_note">
                                <span>Internal Note (not visible to student)</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Response</button>
                    </form>
                </div>
            </div>

            <div class="responses-section">
                <h2>Responses & Notes</h2>
                <?php if (!empty($responses)): ?>
                    <?php foreach ($responses as $response): ?>
                    <div class="response-card <?= $response['is_internal_note'] ? 'internal-note' : '' ?>">
                        <div class="response-header">
                            <span class="response-author">
                                <?php if ($response['is_internal_note']): ?>
                                    📌 <?= htmlspecialchars($response['full_name'] ?? 'Admin') ?>
                                <?php elseif ($response['is_anonymous']): ?>
                                    Anonymous Admin
                                <?php else: ?>
                                    <?= htmlspecialchars($response['full_name'] ?? 'Admin') ?>
                                <?php endif; ?>
                            </span>
                            <span class="response-date"><?= date('M d, Y H:i', strtotime($response['created_at'])) ?></span>
                        </div>
                        <div class="response-text">
                            <?= nl2br(htmlspecialchars($response['response_text'])) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No responses yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function updateStatus(id, status) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);

            fetch('/student-suggestions/public/index.php?route=admin/updateStatus', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Error updating status'));
        }

        function addResponse(event, suggestionId) {
            event.preventDefault();
            
            const formData = new FormData(document.getElementById('responseForm'));
            formData.append('suggestion_id', suggestionId);

            fetch('/student-suggestions/public/index.php?route=admin/addResponse', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Response added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Error adding response'));
        }

        function insertTemplate() {
            const template = document.getElementById('templateSelect').value;
            if (template) {
                const textarea = document.getElementById('responseText');
                textarea.value = template;
            }
        }
    </script>
</body>
</html>
