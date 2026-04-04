<?php
/**
 * Front Controller - Main Entry Point
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoload core classes
spl_autoload_register(function ($class) {
    $coreFile = BASE_PATH . '/app/core/' . $class . '.php';
    if (file_exists($coreFile)) {
        require_once $coreFile;
    }
});

// Load models
require_once BASE_PATH . '/app/models/Suggestion.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/AdminResponse.php';
require_once BASE_PATH . '/app/models/ResponseTemplate.php';
require_once BASE_PATH . '/app/models/Draft.php';

// Get route from query parameter
$route = $_GET['route'] ?? 'student/index';

// Create router and dispatch
$router = new Router();
$router->dispatch($route);