<?php
/**
 * FormBuilder LMS - Question Model
 * 
 * Handles question data and operations
 */

class Question extends BaseModel {
    protected $table = 'questions';
    
    /**
     * Get question with options
     */
    public function getQuestionWithOptions($questionId) {
        $question = $this->read($questionId);
        
        if (!$question) {
            return false;
        }
        
        // Get options
        $sql = "SELECT * FROM options WHERE question_id = ? ORDER BY position";
        $result = $this->query($sql, [$questionId]);
        $question['options'] = $result ? $result->fetchAll() : [];
        
        return $question;
    }
    
    /**
     * Create question with options
     */
    public function createQuestionWithOptions($questionData, $options = []) {
        $questionId = $this->create($questionData);
        
        if (!$questionId) {
            return false;
        }
        
        // Add options
        foreach ($options as $position => $option) {
            $optionData = [
                'question_id' => $questionId,
                'text' => $option['text'] ?? '',
                'is_correct' => $option['is_correct'] ?? false,
                'position' => $position
            ];
            
            $this->query(
                "INSERT INTO options (question_id, text, is_correct, position) VALUES (?, ?, ?, ?)",
                [$optionData['question_id'], $optionData['text'], $optionData['is_correct'], $optionData['position']]
            );
        }
        
        return $questionId;
    }
    
    /**
     * Get section questions
     */
    public function getSectionQuestions($sectionId) {
        $sql = "SELECT * FROM {$this->table} WHERE section_id = ? ORDER BY position";
        $result = $this->query($sql, [$sectionId]);
        return $result ? $result->fetchAll() : [];
    }
}
