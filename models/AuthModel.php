<?php
// include_once '../../config.php';

class AuthModel {
    private $mysqli;

    public function __construct() {
        $this->mysqli = $GLOBALS['mysqli'];
    }
    public function login($username, $password) {
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
                return $user;
        }
        else   return false;
    }
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
    public function register($username, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->mysqli->begin_transaction();
    
        try {
            $stmt = $this->mysqli->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
    
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert user");
            }
    
            $this->mysqli->commit();
            return true;  //Return true when the user is successfully registered
        } catch (Exception $e) {
            $this->mysqli->rollback();
            error_log($e->getMessage());
            return false;  // Return false if registration fails
        }
    }
    
    public function checkUsername($username) {
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0; // Returns true if username exists
    }
    
}
?>