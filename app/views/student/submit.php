<?php
$appName = 'Student Suggestion Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Submit') ?> - <?= $appName ?></title>
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
                <li><a href="/student-suggestions/public/index.php?route=student/submit" class="active">Submit</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/track">Track</a></li>
                <li><a href="/student-suggestions/public/index.php?route=student/viewAll">Previous</a></li>
                <li><a href="/student-suggestions/public/index.php?route=admin/login" class="btn-admin">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="form-page">
                <h1 class="page-title">Submit Your Suggestion</h1>
                <p class="page-subtitle">Your feedback helps us improve. All submissions are confidential.</p>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form class="submission-form" action="/student-suggestions/public/index.php?route=student/submit" method="POST" enctype="multipart/form-data" id="suggestionForm">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required 
                               value="<?= htmlspecialchars($oldInput['title'] ?? '') ?>"
                               placeholder="Brief summary of your suggestion">
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="6" required
                                  placeholder="Provide detailed information about your suggestion or issue"><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category (Optional)</label>
                            <select id="category" name="category">
                                <option value="">Select a category</option>
                                <option value="Academic" <?= ($oldInput['category'] ?? '') === 'Academic' ? 'selected' : '' ?>>Academic</option>
                                <option value="Facilities" <?= ($oldInput['category'] ?? '') === 'Facilities' ? 'selected' : '' ?>>Facilities</option>
                                <option value="Technology" <?= ($oldInput['category'] ?? '') === 'Technology' ? 'selected' : '' ?>>Technology</option>
                                <option value="Campus Life" <?= ($oldInput['category'] ?? '') === 'Campus Life' ? 'selected' : '' ?>>Campus Life</option>
                                <option value="Safety" <?= ($oldInput['category'] ?? '') === 'Safety' ? 'selected' : '' ?>>Safety</option>
                                <option value="Administration" <?= ($oldInput['category'] ?? '') === 'Administration' ? 'selected' : '' ?>>Administration</option>
                                <option value="Other" <?= ($oldInput['category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="urgency_level">Urgency Level</label>
                            <select id="urgency_level" name="urgency_level">
                                <option value="low" <?= ($oldInput['urgency_level'] ?? 'medium') === 'low' ? 'selected' : '' ?>>Low</option>
                                <option value="medium" <?= ($oldInput['urgency_level'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= ($oldInput['urgency_level'] ?? 'medium') === 'high' ? 'selected' : '' ?>>High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="attachment">Attach File (Optional)</label>
                        <input type="file" id="attachment" name="attachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                        <small>Allowed: JPG, PNG, GIF, PDF, DOC, DOCX (Max 5MB)</small>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_anonymous" id="is_anonymous" <?= !isset($oldInput['is_anonymous']) || ($oldInput['is_anonymous'] ?? false) ? 'checked' : '' ?>>
                            <span>Submit Anonymously</span>
                        </label>
                    </div>

                    <div id="personalInfo" style="<?= isset($oldInput['is_anonymous']) && !$oldInput['is_anonymous'] ? '' : 'display:none;' ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="student_name">Your Name</label>
                                <input type="text" id="student_name" name="student_name" 
                                       value="<?= htmlspecialchars($oldInput['student_name'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="student_email">Your Email</label>
                                <input type="email" id="student_email" name="student_email" 
                                       value="<?= htmlspecialchars($oldInput['student_email'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="saveDraft()">Save Draft</button>
                        <button type="submit" class="btn btn-primary btn-lg">Submit Suggestion</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= $appName ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="/student-suggestions/public/assets/js/main.js"></script>
    <script>
        document.getElementById('is_anonymous').addEventListener('change', function() {
            document.getElementById('personalInfo').style.display = this.checked ? 'none' : 'block';
        });

        function saveDraft() {
            const formData = new FormData(document.getElementById('suggestionForm'));
            fetch('/student-suggestions/public/index.php?route=student/saveDraft', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Draft saved successfully! You can continue later.');
                } else {
                    alert('Failed to save draft.');
                }
            })
            .catch(error => alert('Error saving draft.'));
        }
    </script>
</body>
</html>
