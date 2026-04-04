<?php
/**
 * Student Controller - Handles anonymous submissions
 */
require_once __DIR__ . '/../core/Controller.php';

class StudentController extends Controller {
    private $suggestionModel;
    private $draftModel;

    public function __construct() {
        $this->suggestionModel = new Suggestion();
        $this->draftModel = new Draft();
    }

    public function index() {
        $recentSuggestions = $this->suggestionModel->getRecentPublic(5);
        $this->view('student/index', [
            'recentSuggestions' => $recentSuggestions,
            'pageTitle' => 'Submit a Suggestion'
        ]);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $urgencyLevel = $_POST['urgency_level'] ?? 'medium';
            $isAnonymous = isset($_POST['is_anonymous']);
            $studentName = trim($_POST['student_name'] ?? '');
            $studentEmail = trim($_POST['student_email'] ?? '');

            // Validation
            $errors = [];
            if (empty($title)) {
                $errors[] = 'Title is required';
            }
            if (empty($description)) {
                $errors[] = 'Description is required';
            }

            if (!empty($errors)) {
                $this->view('student/submit', [
                    'errors' => $errors,
                    'oldInput' => $_POST,
                    'pageTitle' => 'Submit Suggestion'
                ]);
                return;
            }

            // Handle file upload
            $filePath = null;
            $fileOriginalName = null;
            
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__) . '/public/assets/uploads/';
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
                $maxFileSize = 5 * 1024 * 1024; // 5MB

                $fileTmpName = $_FILES['attachment']['tmp_name'];
                $fileName = $_FILES['attachment']['name'];
                $fileSize = $_FILES['attachment']['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExt, $allowedExtensions)) {
                    $errors[] = 'Invalid file type. Allowed: ' . implode(', ', $allowedExtensions);
                } elseif ($fileSize > $maxFileSize) {
                    $errors[] = 'File size exceeds 5MB limit';
                } else {
                    $newFileName = uniqid('attachment_') . '.' . $fileExt;
                    $destination = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($fileTmpName, $destination)) {
                        $filePath = 'assets/uploads/' . $newFileName;
                        $fileOriginalName = $fileName;
                    }
                }
            }

            if (!empty($errors)) {
                $this->view('student/submit', [
                    'errors' => $errors,
                    'oldInput' => $_POST,
                    'pageTitle' => 'Submit Suggestion'
                ]);
                return;
            }

            // Create suggestion
            $data = [
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'urgency_level' => $urgencyLevel,
                'is_anonymous' => $isAnonymous,
                'student_name' => $isAnonymous ? null : $studentName,
                'student_email' => $isAnonymous ? null : $studentEmail,
                'file_path' => $filePath,
                'file_original_name' => $fileOriginalName,
            ];

            $suggestionId = $this->suggestionModel->create($data);
            $suggestion = $this->suggestionModel->getById($suggestionId);

            // Clear draft if exists
            session_start();
            $sessionId = session_id();
            $this->draftModel->delete($sessionId);

            $this->view('student/confirmation', [
                'ticketCode' => $suggestion['ticket_code'],
                'suggestion' => $suggestion,
                'pageTitle' => 'Submission Confirmed'
            ]);
        } else {
            // Check for existing draft
            session_start();
            $sessionId = session_id();
            $draft = $this->draftModel->getBySessionId($sessionId);

            $this->view('student/submit', [
                'draft' => $draft,
                'errors' => [],
                'oldInput' => $draft ?: [],
                'pageTitle' => 'Submit Suggestion'
            ]);
        }
    }

    public function saveDraft() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $sessionId = session_id();
            
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'category' => $_POST['category'] ?? '',
                'urgency_level' => $_POST['urgency_level'] ?? 'medium',
                'student_name' => $_POST['student_name'] ?? '',
                'student_email' => $_POST['student_email'] ?? '',
            ];

            $this->draftModel->save($sessionId, $data);
            
            $this->jsonResponse(['success' => true, 'message' => 'Draft saved successfully']);
        }
    }

    public function track() {
        $ticketCode = $_GET['ticket'] ?? '';
        $suggestion = null;
        $responses = null;

        if (!empty($ticketCode)) {
            $suggestion = $this->suggestionModel->getByTicketCode($ticketCode);
            if ($suggestion) {
                $responseModel = new AdminResponse();
                $responses = $responseModel->getBySuggestionId($suggestion['id']);
            }
        }

        $this->view('student/track', [
            'ticketCode' => $ticketCode,
            'suggestion' => $suggestion,
            'responses' => $responses,
            'pageTitle' => 'Track Submission'
        ]);
    }

    public function viewAll() {
        $recentSuggestions = $this->suggestionModel->getRecentPublic(20);
        $this->view('student/view_all', [
            'recentSuggestions' => $recentSuggestions,
            'pageTitle' => 'Previous Suggestions'
        ]);
    }
}
