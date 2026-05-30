<?php
/**
 * FormBuilder LMS - Encryptor Utility
 * 
 * Handles encryption and decryption
 */

class Encryptor {
    private $key;
    
    public function __construct() {
        $this->key = getenv('ENCRYPTION_KEY') ?: 'default-encryption-key';
    }
    
    /**
     * Encrypt data
     */
    public function encrypt($data) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Decrypt data
     */
    public function decrypt($data) {
        $data = base64_decode($data);
        $iv_length = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $iv_length);
        $encrypted = substr($data, $iv_length);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, 0, $iv);
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Generate JWT token
     */
    public static function generateJWT($payload, $secret, $expirationTime = 3600) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload['exp'] = time() + $expirationTime;
        $payload = json_encode($payload);
        
        $header = base64_encode($header);
        $payload = base64_encode($payload);
        
        $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
        $signature = base64_encode($signature);
        
        return "$header.$payload.$signature";
    }
    
    /**
     * Verify JWT token
     */
    public static function verifyJWT($token, $secret) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];
        
        $expectedSignature = base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true));
        
        if ($signature !== $expectedSignature) {
            return false;
        }
        
        $payloadData = json_decode(base64_decode($payload), true);
        
        if ($payloadData['exp'] < time()) {
            return false;
        }
        
        return $payloadData;
    }
}
