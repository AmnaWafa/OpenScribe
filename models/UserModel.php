<?php
include_once '../../config.php';

class UserModel {
    private $mysqli;

    public function __construct() {
        $this->mysqli = $GLOBALS['mysqli'];
    }

    public function getUserId($userId) {
        $stmt = $this->mysqli->prepare("
            SELECT id 
            FROM users 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['id'] : null;
    }
    public function getUserByUsername($userId) {
        $stmt = $this->mysqli->prepare("
            SELECT username 
            FROM users 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['username'] : null;
    }
    public function getPublishedBlogs($username) {
        $query = "SELECT blog.id, blog.username, blog.title, blog.created_at FROM blog LEFT JOIN users ON blog.username = users.username WHERE blog.status = 'published' AND blog.username = '$username'";
        $result = $this->mysqli->query($query);
    
        if (!$result) {
            die("Error in query: " . $this->mysqli->error); 
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getPendingBlogs($username) {
        $query = "SELECT blog.id, blog.username, blog.title, blog.created_at FROM blog LEFT JOIN users ON blog.username = users.username WHERE blog.status = 'pending' AND blog.username = '$username'";
        $result = $this->mysqli->query($query);
    
        if (!$result) {
            die("Error in query: " . $this->mysqli->error); 
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}