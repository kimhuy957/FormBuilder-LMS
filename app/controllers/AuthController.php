<?php
/**
 * FormBuilder LMS - Authentication Controller
 * 
 * Handles user authentication
 */

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    /**
     * Login action
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = Sanitizer::sanitizeEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate input
            if (!Validator::validateEmail($email) || empty($password)) {
                $this->error('Invalid email or password', [], 400);
            }
            
            // Verify credentials
            $user = $this->userModel->verifyPassword($email, $password);
            
            if (!$user) {
                $this->logger->warning('Login failed', ['email' => $email]);
                $this->error('Invalid credentials', [], 401);
            }
            
            // Set session
            session_regenerate_id();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_data'] = $user;
            
            $this->logger->info('User logged in', ['user_id' => $user['id']]);
            
            $this->success('Login successful', ['user' => $user]);
        }
        
        echo $this->render('auth/login');
    }
    
    /**
     * Register action
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => Sanitizer::sanitizeString($_POST['first_name'] ?? ''),
                'last_name' => Sanitizer::sanitizeString($_POST['last_name'] ?? ''),
                'email' => Sanitizer::sanitizeEmail($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'password_confirm' => $_POST['password_confirm'] ?? ''
            ];
            
            // Validate
            if (!Validator::validateEmail($data['email'])) {
                $this->error('Invalid email', ['email' => 'Invalid email address'], 400);
            }
            
            if ($data['password'] !== $data['password_confirm']) {
                $this->error('Passwords do not match', ['password' => 'Passwords do not match'], 400);
            }
            
            if (!Validator::minLength($data['password'], 8)) {
                $this->error('Password too short', ['password' => 'Password must be at least 8 characters'], 400);
            }
            
            // Check if email exists
            if ($this->userModel->emailExists($data['email'])) {
                $this->error('Email already registered', ['email' => 'This email is already registered'], 400);
            }
            
            // Create user
            $userId = $this->userModel->createUser([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            
            if (!$userId) {
                $this->error('Registration failed', [], 500);
            }
            
            $this->logger->info('New user registered', ['user_id' => $userId]);
            
            $this->success('Registration successful. Please login.', ['user_id' => $userId]);
        }
        
        echo $this->render('auth/register');
    }
    
    /**
     * Logout action
     */
    public function logout() {
        session_destroy();
        $this->logger->info('User logged out');
        Helper::redirect('/');
    }
    
    /**
     * Forgot password action
     */
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = Sanitizer::sanitizeEmail($_POST['email'] ?? '');
            
            if (!Validator::validateEmail($email)) {
                $this->error('Invalid email', [], 400);
            }
            
            $user = $this->userModel->findByEmail($email);
            
            if (!$user) {
                // Don't reveal if email exists
                $this->success('If this email exists, a reset link has been sent', []);
            }
            
            // Generate reset token
            $token = Helper::generateToken();
            $this->userModel->update($user['id'], [
                'reset_token' => $token,
                'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ]);
            
            $this->logger->info('Password reset requested', ['user_id' => $user['id']]);
            $this->success('Reset link sent to your email', []);
        }
        
        echo $this->render('auth/forgot-password');
    }
}
