<?php
session_start(); 
include_once '../config.php';
include_once '../models/BlogModel.php';

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            include_once '../components/admin-header.php';
            break;
        case 'user':
            include_once '../components/user-header.php';
            break;
    }
}

$blogId = $_GET['id'] ?? null;
if (!$blogId) {
    echo "Invalid blog ID.";
    exit;
}

$blogModel = new BlogModel();
$blog = $blogModel->getBlogById($blogId);

if (!$blog) {
    echo "Blog not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
</head>
<body>
<?php 
if (isset($_GET['blog_created']) && $_GET['blog_created'] === 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Holy guacamole!</strong> You should check in on some of those fields below.
 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
</div>';
}
?>
    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($blog['username']); ?></p>
 
    <p><strong>Published on:</strong> <?php echo date("F j, Y, g:i a", strtotime($blog['created_at'])); ?></p>
    <div>
    <?php echo $blog['content']; ?>
    <?php if($blog['status']==='pending'):?>
        <a href="publish-blog.php?id=<?php echo urlencode ($blog['id']);?>"><button type="submit">Publish Blog</button></a>
    <?php endif; ?>
    </div>
</body>
</html>
