<?php 
include_once '../config.php';
session_start();
function redirect($url) {
    header("Location: $url");
    exit();
}

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
    redirect('/blog-app/logic/view-blog.php?id='. $blogId . '&blog_published=true');
} else {
    redirect('/blog-app/logic/write-blog.php?failed=true');

}
$stmt->close();
?>