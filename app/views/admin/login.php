<?php
$appName = 'Student Suggestion Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Login') ?> - <?= $appName ?></title>
    <link rel="stylesheet" href="/student-suggestions/public/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <span class="logo-icon">🔐</span>
                <h1>Admin Login</h1>
                <p>Sign in to manage suggestions</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form class="login-form" action="/student-suggestions/public/index.php?route=admin/login" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>

            <div class="login-footer">
                <a href="/student-suggestions/public/index.php">← Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
