<?php
/**
 * Application Configuration
 */
return [
    'app_name' => 'Student Suggestion Portal',
    'base_url' => 'http://localhost/student-suggestions/public',
    'upload_dir' => dirname(__DIR__) . '/public/assets/uploads',
    'max_file_size' => 5 * 1024 * 1024, // 5MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
    'session_lifetime' => 3600,
];
