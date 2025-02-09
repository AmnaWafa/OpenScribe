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
        if($user['status'] === 'disabled'){
            return false;
        }
        else{
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        }
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
        } catch (Exception $e) {
            $this->mysqli->rollback();
            error_log($e->getMessage());
            return false;
        }
}
}
?>