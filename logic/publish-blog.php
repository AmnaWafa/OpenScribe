<?php 
include_once '../config.php';
session_start();

// Ensure the user is logged in
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: ../guest/login.php');
    exit;
}
$blogId = $_GET['id'] ?? null;
if (!$blogId) {
    echo "Invalid blog ID.";
    exit;
}
$query = "UPDATE blog SET status = 'published' WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $blogId);
if ($stmt->execute()) {
    echo "Blog published successfully! <a href='view-blog.php?id=" . $blogId . "'>View Blog</a>";
} else {
    echo "Error: " . $stmt1->error;
}
$stmt->close();
?>