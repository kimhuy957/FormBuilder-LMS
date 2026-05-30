<?php
/**
 * FormBuilder LMS - Logger Utility
 * 
 * Handles application logging
 */

class Logger {
    private $logFile;
    private $logLevel;
    
    public function __construct() {
        $this->logFile = 'logs/app.log';
        $this->ensureLogFileExists();
    }
    
    /**
     * Ensure log file and directory exist
     */
    private function ensureLogFileExists() {
        if (!is_dir('logs')) {
            mkdir('logs', 0777, true);
        }
        
        if (!file_exists($this->logFile)) {
            fopen($this->logFile, 'w');
        }
    }
    
    /**
     * Log info message
     */
    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }
    
    /**
     * Log error message
     */
    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }
    
    /**
     * Log warning message
     */
    public function warning($message, $context = []) {
        $this->log('WARNING', $message, $context);
    }
    
    /**
     * Log debug message
     */
    public function debug($message, $context = []) {
        if (getenv('APP_DEBUG')) {
            $this->log('DEBUG', $message, $context);
        }
    }
    
    /**
     * Log message
     */
    private function log($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $logMessage = "[$timestamp] [$level] $message $contextStr" . PHP_EOL;
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
