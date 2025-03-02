<?php 
include_once '../config.php';
include_once '../models/UserModel.php';
session_start();
function redirect($url) {
    header("Location: $url");
    exit();
}

// Ensure the user is logged in
$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['role'] ?? '';

if (!$userId) {
    header('Location: ../guest/login.php');
    exit;
}
if (!$userRole) {
    header('Location: ../guest/login.php');
    exit;
}
if ($userRole==='admin') {
    header('Location: ../admin/dashboard.php');
    exit;
}
$userModel = new UserModel();
$user = $userModel->getUserByUserID($userId);
if (!$user) {
    echo "User not found.";
    exit;
}

$username = $user['username']; // Correctly fetch the username
$title = $_POST['title'];
$content = $_POST['content'];

// Insert the blog into the `blog` table
$sql = "INSERT INTO blog (username, title) VALUES (?, ?)"; 
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $username, $title);
$stmt->execute();
$blog_id = $stmt->insert_id; // Get the newly inserted blog ID
$stmt->close();

// Insert the blog content into the `blog_details` table
$query = "INSERT INTO blog_detail (id, content) VALUES (?, ?)";
$stmt1 = $mysqli->prepare($query);
$stmt1->bind_param("is", $blog_id, $content);
if ($stmt1->execute()) {
    // echo "Blog saved successfully! <a href='view-blog.php?id=" . $blog_id . "'>View Blog</a>";
    redirect('/blog-app/logic/view-blog.php?id='. $blog_id . '&blog_created=true');

} else {
    redirect('/blog-app/logic/write-blog.php?failed=true');

}
$stmt1->close();
?>
