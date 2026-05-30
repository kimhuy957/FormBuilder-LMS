<?php
/**
 * FormBuilder LMS - Validator Utility
 * 
 * Validates user input against rules
 */

class Validator {
    private $errors = [];
    
    /**
     * Validate email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate URL
     */
    public static function validateUrl($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate required field
     */
    public static function required($value) {
        return !empty($value) && trim($value) !== '';
    }
    
    /**
     * Validate minimum length
     */
    public static function minLength($value, $length) {
        return strlen($value) >= $length;
    }
    
    /**
     * Validate maximum length
     */
    public static function maxLength($value, $length) {
        return strlen($value) <= $length;
    }
    
    /**
     * Validate match between two values
     */
    public static function match($value1, $value2) {
        return $value1 === $value2;
    }
    
    /**
     * Validate phone number
     */
    public static function validatePhone($phone) {
        return preg_match('/^[0-9\s\-\+\(\)]{10,}$/', $phone);
    }
    
    /**
     * Validate date format
     */
    public static function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Validate data against multiple rules
     */
    public function validate($data, $rules) {
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule) {
                $this->validateRule($field, $value, $rule);
            }
        }
        
        return count($this->errors) === 0;
    }
    
    /**
     * Validate single rule
     */
    private function validateRule($field, $value, $rule) {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleParam = $ruleParts[1] ?? null;
        
        switch ($ruleName) {
            case 'required':
                if (!self::required($value)) {
                    $this->errors[$field][] = "$field is required";
                }
                break;
            case 'email':
                if ($value && !self::validateEmail($value)) {
                    $this->errors[$field][] = "$field must be a valid email";
                }
                break;
            case 'url':
                if ($value && !self::validateUrl($value)) {
                    $this->errors[$field][] = "$field must be a valid URL";
                }
                break;
            case 'min':
                if ($value && !self::minLength($value, $ruleParam)) {
                    $this->errors[$field][] = "$field must be at least $ruleParam characters";
                }
                break;
            case 'max':
                if ($value && !self::maxLength($value, $ruleParam)) {
                    $this->errors[$field][] = "$field must not exceed $ruleParam characters";
                }
                break;
            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->errors[$field][] = "$field must be numeric";
                }
                break;
            case 'phone':
                if ($value && !self::validatePhone($value)) {
                    $this->errors[$field][] = "$field must be a valid phone number";
                }
                break;
        }
    }
    
    /**
     * Get validation errors
     */
    public function getErrors() {
        return $this->errors;
    }
}
