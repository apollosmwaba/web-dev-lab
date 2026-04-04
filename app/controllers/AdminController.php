<?php
/**
 * Admin Controller - Handles admin/staff operations
 */
require_once __DIR__ . '/../core/Controller.php';

class AdminController extends Controller {
    private $suggestionModel;
    private $userModel;
    private $responseModel;
    private $templateModel;

    public function __construct() {
        $this->suggestionModel = new Suggestion();
        $this->userModel = new User();
        $this->responseModel = new AdminResponse();
        $this->templateModel = new ResponseTemplate();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $this->view('admin/login', [
                    'error' => 'Username and password are required',
                    'pageTitle' => 'Admin Login'
                ]);
                return;
            }

            $user = $this->userModel->getByUsername($username);

            if ($user && $this->userModel->verifyPassword($password, $user['password_hash'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                $this->redirect('/student-suggestions/public/index.php?route=admin/dashboard');
            } else {
                $this->view('admin/login', [
                    'error' => 'Invalid username or password',
                    'pageTitle' => 'Admin Login'
                ]);
            }
        } else {
            if ($this->isLoggedIn()) {
                $this->redirect('/student-suggestions/public/index.php?route=admin/dashboard');
            }
            $this->view('admin/login', ['error' => null, 'pageTitle' => 'Admin Login']);
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirect('/student-suggestions/public/index.php?route=admin/login');
    }

    public function dashboard() {
        $this->requireLogin();
        
        $stats = $this->suggestionModel->getStats();
        $recentSuggestions = $this->suggestionModel->getAll(null, 'created_at DESC LIMIT 10');
        
        $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentSuggestions' => $recentSuggestions,
            'pageTitle' => 'Dashboard'
        ]);
    }

    public function suggestions() {
        $this->requireLogin();
        
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $suggestions = $this->suggestionModel->search($search);
        } elseif ($status) {
            $suggestions = $this->suggestionModel->getAll($status);
        } else {
            $suggestions = $this->suggestionModel->getAll();
        }
        
        $this->view('admin/suggestions', [
            'suggestions' => $suggestions,
            'currentStatus' => $status,
            'search' => $search,
            'pageTitle' => 'All Suggestions'
        ]);
    }

    public function viewSuggestion($id) {
        $this->requireLogin();
        
        $suggestion = $this->suggestionModel->getById($id);
        
        if (!$suggestion) {
            $this->redirect('/student-suggestions/public/index.php?route=admin/suggestions');
        }
        
        $responses = $this->responseModel->getBySuggestionId($id, true);
        $internalNotes = $this->responseModel->getInternalNotes($id);
        $templates = $this->templateModel->getAll();
        
        $this->view('admin/view_suggestion', [
            'suggestion' => $suggestion,
            'responses' => $responses,
            'internalNotes' => $internalNotes,
            'templates' => $templates,
            'pageTitle' => 'View Suggestion #' . $id
        ]);
    }

    public function updateStatus() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            $validStatuses = ['new', 'in_review', 'resolved', 'actioned'];
            
            if (!in_array($status, $validStatuses)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid status'], 400);
            }
            
            session_start();
            $userId = $_SESSION['user_id'];
            
            $this->suggestionModel->updateStatus($id, $status, $userId);
            
            $this->jsonResponse(['success' => true, 'message' => 'Status updated successfully']);
        }
    }

    public function addResponse() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            
            $data = [
                'suggestion_id' => $_POST['suggestion_id'] ?? 0,
                'user_id' => $_SESSION['user_id'],
                'response_text' => trim($_POST['response_text'] ?? ''),
                'is_anonymous' => isset($_POST['is_anonymous']),
                'is_internal_note' => isset($_POST['is_internal_note']),
            ];
            
            if (empty($data['response_text'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Response text is required'], 400);
            }
            
            $this->responseModel->create($data);
            
            $this->jsonResponse(['success' => true, 'message' => 'Response added successfully']);
        }
    }

    public function bulkUpdate() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'] ?? [];
            $status = $_POST['status'] ?? '';
            
            if (empty($ids) || empty($status)) {
                $this->jsonResponse(['success' => false, 'message' => 'No items selected or invalid status'], 400);
            }
            
            session_start();
            $userId = $_SESSION['user_id'];
            $validStatuses = ['new', 'in_review', 'resolved', 'actioned'];
            
            if (!in_array($status, $validStatuses)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid status'], 400);
            }
            
            foreach ($ids as $id) {
                $this->suggestionModel->updateStatus((int)$id, $status, $userId);
            }
            
            $this->jsonResponse(['success' => true, 'message' => count($ids) . ' items updated successfully']);
        }
    }

    public function export() {
        $this->requireLogin();
        
        $suggestions = $this->suggestionModel->getAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="suggestions_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Ticket Code', 'Title', 'Category', 'Urgency', 'Status', 'Created At', 'Resolved At']);
        
        foreach ($suggestions as $suggestion) {
            fputcsv($output, [
                $suggestion['id'],
                $suggestion['ticket_code'],
                $suggestion['title'],
                $suggestion['category'],
                $suggestion['urgency_level'],
                $suggestion['status'],
                $suggestion['created_at'],
                $suggestion['resolved_at'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function templates() {
        $this->requireLogin();
        
        $templates = $this->templateModel->getAll();
        
        $this->view('admin/templates', [
            'templates' => $templates,
            'pageTitle' => 'Response Templates'
        ]);
    }

    public function addTemplate() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'template_name' => trim($_POST['template_name'] ?? ''),
                'template_content' => trim($_POST['template_content'] ?? ''),
                'category' => trim($_POST['category'] ?? 'General'),
            ];
            
            if (empty($data['template_name']) || empty($data['template_content'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Name and content are required'], 400);
            }
            
            $this->templateModel->create($data);
            $this->jsonResponse(['success' => true, 'message' => 'Template created successfully']);
        }
    }

    public function deleteTemplate() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $this->templateModel->delete($id);
            $this->jsonResponse(['success' => true, 'message' => 'Template deleted successfully']);
        }
    }
}
