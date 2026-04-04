<?php
/**
 * Base Controller Class
 */
class Controller {
    protected function view($viewName, $data = []) {
        extract($data);
        include __DIR__ . '/../views/' . str_replace('.', '/', $viewName) . '.php';
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/student-suggestions/public/index.php?route=admin/login');
        }
    }

    protected function getRole() {
        return $_SESSION['user_role'] ?? null;
    }

    protected function hasRole($allowedRoles) {
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        return in_array($this->getRole(), $allowedRoles);
    }
}
