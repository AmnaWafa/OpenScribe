<?php 
include_once '../config.php';
session_start();

// Ensure the user is logged in
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: ../guest/login.php');
    exit;
}

// Fetch the username from the database based on the user ID
$query = "SELECT username FROM users WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

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
    echo "Blog saved successfully! <a href='view-blog.php?id=" . $blog_id . "'>View Blog</a>";
} else {
    echo "Error: " . $stmt1->error;
}
$stmt1->close();
?>
