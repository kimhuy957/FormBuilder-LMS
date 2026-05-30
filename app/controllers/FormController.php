<?php
/**
 * FormBuilder LMS - Form Controller
 * 
 * Handles form operations
 */

class FormController extends BaseController {
    private $formModel;
    private $questionModel;
    
    public function __construct() {
        parent::__construct();
        $this->formModel = new Form();
        $this->questionModel = new Question();
    }
    
    /**
     * List forms
     */
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $forms = $this->formModel->getFormsByTeacher($user['id'], $limit, $offset);
        
        echo $this->render('forms/list', ['forms' => $forms, 'page' => $page]);
    }
    
    /**
     * Create form
     */
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            
            $data = [
                'title' => Sanitizer::sanitizeString($_POST['title'] ?? ''),
                'description' => Sanitizer::sanitizeString($_POST['description'] ?? ''),
                'created_by' => $user['id']
            ];
            
            if (empty($data['title'])) {
                $this->error('Title is required', [], 400);
            }
            
            $formId = $this->formModel->createForm($data);
            
            if (!$formId) {
                $this->error('Failed to create form', [], 500);
            }
            
            $this->logger->info('Form created', ['form_id' => $formId, 'user_id' => $user['id']]);
            
            $this->success('Form created successfully', ['form_id' => $formId]);
        }
        
        echo $this->render('forms/create');
    }
    
    /**
     * Edit form
     */
    public function edit($id) {
        $this->requireAuth();
        
        $form = $this->formModel->getFormWithQuestions($id);
        
        if (!$form) {
            $this->error('Form not found', [], 404);
        }
        
        $user = $this->getCurrentUser();
        
        if ($form['created_by'] != $user['id']) {
            $this->error('Unauthorized', [], 403);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => Sanitizer::sanitizeString($_POST['title'] ?? ''),
                'description' => Sanitizer::sanitizeString($_POST['description'] ?? ''),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->formModel->update($id, $data);
            $this->logger->info('Form updated', ['form_id' => $id]);
            
            $this->success('Form updated successfully', ['form_id' => $id]);
        }
        
        echo $this->render('forms/edit', ['form' => $form]);
    }
    
    /**
     * Delete form
     */
    public function delete($id) {
        $this->requireAuth();
        
        $form = $this->formModel->read($id);
        
        if (!$form) {
            $this->error('Form not found', [], 404);
        }
        
        $user = $this->getCurrentUser();
        
        if ($form['created_by'] != $user['id']) {
            $this->error('Unauthorized', [], 403);
        }
        
        $this->formModel->delete($id);
        $this->logger->info('Form deleted', ['form_id' => $id]);
        
        $this->success('Form deleted successfully', []);
    }
    
    /**
     * Publish form
     */
    public function publish($id) {
        $this->requireAuth();
        
        $form = $this->formModel->read($id);
        
        if (!$form) {
            $this->error('Form not found', [], 404);
        }
        
        $user = $this->getCurrentUser();
        
        if ($form['created_by'] != $user['id']) {
            $this->error('Unauthorized', [], 403);
        }
        
        $this->formModel->publishForm($id);
        $this->logger->info('Form published', ['form_id' => $id]);
        
        $this->success('Form published successfully', []);
    }
}
