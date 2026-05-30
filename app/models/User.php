<?php
/**
 * FormBuilder LMS - User Model
 * 
 * Handles user data and operations
 */

class User extends BaseModel {
    protected $table = 'users';
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        return $this->findOneBy('email', $email);
    }
    
    /**
     * Create new user with password hashing
     */
    public function createUser($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
    
    /**
     * Verify user password
     */
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Get user roles
     */
    public function getRoles($userId) {
        $sql = "SELECT r.* FROM roles r 
                INNER JOIN user_roles ur ON r.id = ur.role_id 
                WHERE ur.user_id = ?";
        
        $result = $this->query($sql, [$userId]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Check if user has role
     */
    public function hasRole($userId, $roleName) {
        $sql = "SELECT COUNT(*) as count FROM user_roles ur 
                INNER JOIN roles r ON ur.role_id = r.id 
                WHERE ur.user_id = ? AND r.name = ?";
        
        $result = $this->query($sql, [$userId, $roleName]);
        $row = $result->fetch();
        
        return $row['count'] > 0;
    }
    
    /**
     * Assign role to user
     */
    public function assignRole($userId, $roleId) {
        $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
        return $this->query($sql, [$userId, $roleId]);
    }
    
    /**
     * Update password
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    /**
     * Get user by ID with roles
     */
    public function getUserWithRoles($userId) {
        $user = $this->read($userId);
        if ($user) {
            $user['roles'] = $this->getRoles($userId);
        }
        return $user;
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email) {
        return $this->findByEmail($email) !== false;
    }
}
