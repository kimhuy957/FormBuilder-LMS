<?php
/**
 * FormBuilder LMS - Form Model
 * 
 * Handles form data and operations
 */

class Form extends BaseModel {
    protected $table = 'forms';
    
    /**
     * Create a new form
     */
    public function createForm($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'draft';
        return $this->create($data);
    }
    
    /**
     * Get form with all questions
     */
    public function getFormWithQuestions($formId) {
        $form = $this->read($formId);
        
        if (!$form) {
            return false;
        }
        
        // Get sections
        $sql = "SELECT * FROM sections WHERE form_id = ? ORDER BY position";
        $result = $this->query($sql, [$formId]);
        $form['sections'] = $result ? $result->fetchAll() : [];
        
        // Get questions for each section
        foreach ($form['sections'] as &$section) {
            $sql = "SELECT * FROM questions WHERE section_id = ? ORDER BY position";
            $result = $this->query($sql, [$section['id']]);
            $section['questions'] = $result ? $result->fetchAll() : [];
            
            // Get options for each question
            foreach ($section['questions'] as &$question) {
                $sql = "SELECT * FROM options WHERE question_id = ? ORDER BY position";
                $result = $this->query($sql, [$question['id']]);
                $question['options'] = $result ? $result->fetchAll() : [];
            }
        }
        
        return $form;
    }
    
    /**
     * Publish form
     */
    public function publishForm($formId) {
        return $this->update($formId, [
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Archive form
     */
    public function archiveForm($formId) {
        return $this->update($formId, [
            'status' => 'archived',
            'archived_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get forms by teacher
     */
    public function getFormsByTeacher($teacherId, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE created_by = ? ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $result = $this->query($sql, [$teacherId]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get form submissions
     */
    public function getSubmissions($formId) {
        $sql = "SELECT * FROM submissions WHERE form_id = ? ORDER BY created_at DESC";
        $result = $this->query($sql, [$formId]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get form statistics
     */
    public function getStatistics($formId) {
        $stats = [];
        
        // Total submissions
        $sql = "SELECT COUNT(*) as count FROM submissions WHERE form_id = ?";
        $result = $this->query($sql, [$formId]);
        $stats['total_submissions'] = $result->fetch()['count'] ?? 0;
        
        // Average score
        $sql = "SELECT AVG(score) as avg_score FROM submissions WHERE form_id = ?";
        $result = $this->query($sql, [$formId]);
        $stats['average_score'] = $result->fetch()['avg_score'] ?? 0;
        
        // Completion rate
        $sql = "SELECT COUNT(*) as count FROM submissions WHERE form_id = ? AND status = 'completed'";
        $result = $this->query($sql, [$formId]);
        $stats['completed'] = $result->fetch()['count'] ?? 0;
        
        return $stats;
    }
}
