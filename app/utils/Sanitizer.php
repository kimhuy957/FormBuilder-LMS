<?php
/**
 * FormBuilder LMS - Sanitizer Utility
 * 
 * Sanitizes and validates user input
 */

class Sanitizer {
    /**
     * Sanitize string input
     */
    public static function sanitizeString($input) {
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitize email
     */
    public static function sanitizeEmail($email) {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Sanitize integer
     */
    public static function sanitizeInt($input) {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
     * Sanitize float
     */
    public static function sanitizeFloat($input) {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    /**
     * Sanitize URL
     */
    public static function sanitizeUrl($url) {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
    
    /**
     * Sanitize file name
     */
    public static function sanitizeFileName($fileName) {
        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
        return $fileName;
    }
    
    /**
     * Sanitize array of inputs
     */
    public static function sanitizeArray($data, $rules = []) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                
                switch ($rule) {
                    case 'string':
                        $sanitized[$key] = self::sanitizeString($value);
                        break;
                    case 'email':
                        $sanitized[$key] = self::sanitizeEmail($value);
                        break;
                    case 'int':
                        $sanitized[$key] = self::sanitizeInt($value);
                        break;
                    case 'float':
                        $sanitized[$key] = self::sanitizeFloat($value);
                        break;
                    case 'url':
                        $sanitized[$key] = self::sanitizeUrl($value);
                        break;
                    default:
                        $sanitized[$key] = self::sanitizeString($value);
                }
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }
        
        return $sanitized;
    }
}
