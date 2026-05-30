<?php
/**
 * FormBuilder LMS - Helper Utility
 * 
 * Common helper functions
 */

class Helper {
    /**
     * Get file size in human readable format
     */
    public static function getHumanFilesize($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Format date
     */
    public static function formatDate($date, $format = 'd/m/Y H:i:s') {
        return date($format, strtotime($date));
    }
    
    /**
     * Generate slug
     */
    public static function generateSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Get current user
     */
    public static function getCurrentUser() {
        return $_SESSION['user_data'] ?? null;
    }
    
    /**
     * Redirect to URL
     */
    public static function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
    
    /**
     * Get base URL
     */
    public static function getBaseUrl() {
        return getenv('APP_URL') ?: 'http://localhost';
    }
    
    /**
     * Generate random token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Check if string starts with
     */
    public static function startsWith($string, $prefix) {
        return strpos($string, $prefix) === 0;
    }
    
    /**
     * Check if string ends with
     */
    public static function endsWith($string, $suffix) {
        return substr($string, -strlen($suffix)) === $suffix;
    }
    
    /**
     * Convert array to JSON
     */
    public static function toJson($data) {
        return json_encode($data);
    }
    
    /**
     * Parse JSON to array
     */
    public static function parseJson($json) {
        return json_decode($json, true);
    }
}
