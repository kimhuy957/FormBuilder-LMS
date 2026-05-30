<?php
/**
 * FormBuilder LMS - Database Configuration
 * 
 * Handles all database connection and configuration
 */

class Database {
    private $host;
    private $db_name;
    private $user;
    private $password;
    private $port;
    private $conn;
    
    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'formbuilder_lms';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->port = getenv('DB_PORT') ?: 3306;
    }
    
    /**
     * Connect to database using PDO
     */
    public function connect() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . 
                   ";port=" . $this->port . 
                   ";dbname=" . $this->db_name . 
                   ";charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            return $this->conn;
        } catch(PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            die('Database connection failed');
        }
    }
    
    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Close database connection
     */
    public function close() {
        $this->conn = null;
    }
}
