<?php
/**
 * FormBuilder LMS - Base Controller
 * 
 * Abstract base controller with common functionality
 */

abstract class BaseController {
    protected $view;
    protected $logger;
    protected $validator;
    protected $sanitizer;
    
    public function __construct() {
        $this->logger = new Logger();
        $this->validator = new Validator();
    }
    
    /**
     * Render view
     */
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = 'app/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            $this->logger->error("View not found: $view");
            die("View not found: $view");
        }
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }
    
    /**
     * Success response
     */
    protected function success($message, $data = null, $statusCode = 200) {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    /**
     * Error response
     */
    protected function error($message, $errors = null, $statusCode = 400) {
        $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Get current user
     */
    protected function getCurrentUser() {
        return $_SESSION['user_data'] ?? null;
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->error('Unauthorized', null, 401);
        }
    }
    
    /**
     * Get request data
     */
    protected function getRequestData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            return $data ?? $_POST ?? [];
        }
        return $_GET ?? [];
    }
}
