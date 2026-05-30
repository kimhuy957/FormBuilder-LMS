<?php
/**
 * FormBuilder LMS - Submission Model
 * 
 * Handles submission data and operations
 */

class Submission extends BaseModel {
    protected $table = 'submissions';
    
    /**
     * Create submission
     */
    public function createSubmission($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'in_progress';
        return $this->create($data);
    }
    
    /**
     * Get submission with answers
     */
    public function getSubmissionWithAnswers($submissionId) {
        $submission = $this->read($submissionId);
        
        if (!$submission) {
            return false;
        }
        
        // Get answers
        $sql = "SELECT * FROM answers WHERE submission_id = ?";
        $result = $this->query($sql, [$submissionId]);
        $submission['answers'] = $result ? $result->fetchAll() : [];
        
        return $submission;
    }
    
    /**
     * Add answer to submission
     */
    public function addAnswer($submissionId, $questionId, $answer, $score = 0) {
        $sql = "INSERT INTO answers (submission_id, question_id, answer, score) VALUES (?, ?, ?, ?)";
        return $this->query($sql, [$submissionId, $questionId, $answer, $score]);
    }
    
    /**
     * Complete submission
     */
    public function completeSubmission($submissionId, $score, $grade) {
        return $this->update($submissionId, [
            'status' => 'completed',
            'score' => $score,
            'grade' => $grade,
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get student submissions for form
     */
    public function getStudentSubmissions($studentId, $formId) {
        $sql = "SELECT * FROM {$this->table} WHERE student_id = ? AND form_id = ? ORDER BY created_at DESC";
        $result = $this->query($sql, [$studentId, $formId]);
        return $result ? $result->fetchAll() : [];
    }
}
