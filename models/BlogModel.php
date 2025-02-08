<?php
include_once '../../config.php';

class BlogModel {
    private $mysqli;

    public function __construct() {
        $this->mysqli = $GLOBALS['mysqli'];
    }
    public function getBlogById($blogId) {
        $stmt = $this->mysqli->prepare("
            SELECT bd.*, b.id, b.title, b.username, b.created_at, b.status, u.username
            FROM blog_detail bd
            LEFT JOIN blog b ON bd.id = b.id
            LEFT JOIN users u ON b.username = u.username
            WHERE b.id = ?
        ");
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getAllBlogs(){
        $query = "
            SELECT 
                blog.username,
                blog.description,
                blog.created_at
            FROM 
                blog
            LEFT JOIN 
                users ON blog.username = users.username
        ";
        $result = $this->mysqli->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAllPublishedBlogs() {
        $query = "SELECT id, username, title, created_at FROM blog WHERE status = 'published'";
        $result = $this->mysqli->query($query);
    
        if (!$result) {
            die("Error in query: " . $this->mysqli->error); // Debugging
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAllPendingBlogs() {
        $query = "SELECT id, username, title, created_at FROM blog WHERE status = 'pending'";
        $result = $this->mysqli->query($query);
    
        if (!$result) {
            die("Error in query: " . $this->mysqli->error); // Debugging
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getTotalBlogs() {
        $query = "SELECT COUNT(*) AS total FROM blog";
        $result = $this->mysqli->query($query);
        return $result->fetch_assoc()['total'];
    }
    public function getTotalPublishedBlogs() {
        $query = "SELECT COUNT(*) AS total FROM blog WHERE status = 'published' ";
        $result = $this->mysqli->query($query);
        return $result->fetch_assoc()['total'];
    }
    public function getTotalPendingBlogs() {
        $query = "SELECT COUNT(*) AS total FROM blog WHERE status = 'pending' ";
        $result = $this->mysqli->query($query);
        return $result->fetch_assoc()['total'];
    }
    public function deleteBlog($blog_id) {
        $query = "DELETE FROM blog WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $blog_id);
    
        if ($stmt->execute()) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }
}
?>