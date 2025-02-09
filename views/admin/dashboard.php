<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit;
}
include_once '../../config.php';
include_once '../../models/BlogModel.php';
include_once '../../components/admin-header.php';
$blogModel = new BlogModel();
$blogs = $blogModel->getAllPublishedBlogs();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['blog_id'])) {
    $blog_id = $_POST['blog_id']; 
    if ($blogModel->deleteBlog($blog_id)) {
        echo "<script>alert('Blog deleted successfully!')</script>";
        header('Location: dashboard.php');
    } else {
        echo "<script>alert('Error deleting blog!');</script>";
        header('Location: dashboard.php');
    }
} 
?>
<div class="container mt-5">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin panel. Here you can manage blogs, and users.</p>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Blogs</h5>
                    <p class="card-text">
                    <?php 
                        $totalBlogs = $blogModel->getTotalBlogs();
                        echo $totalBlogs !== null ? $totalBlogs : "Error fetching blogs";
                    ?>
                    </p> 
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Published Blogs</h5>
                    <p class="card-text">
                    <?php
                        $totalPublishedBlogs = $blogModel->getTotalPublishedBlogs();
                        echo $totalPublishedBlogs !== null ? $totalPublishedBlogs : "Error fetching blogs";
                    ?>
                    </p> 
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5">
    <h2 class="text-center">Published Blogs</h2>
    <div class="blog-container">
    <?php if (!empty($blogs) && is_array($blogs)): ?>
        <?php foreach ($blogs as $blog): ?>
            <a href="../../logic/view-blog.php?id=<?php echo urlencode($blog['id']); ?>" class="text-decoration-none">
            <div class="blog-card card card-body">
                <p><?php echo htmlspecialchars($blog['username']); ?></p>
                <h3 class="card-title"><?php echo nl2br(htmlspecialchars($blog['title'])); ?></h3>
                <p class="timestamp"><?php echo date("F j, Y, g:i a", strtotime($blog['created_at'])); ?></p>
                <form method="POST" onsubmit="return confirmDelete();">
                    <input type="hidden" name="blog_id" value="<?= htmlspecialchars($blog['id']) ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No blogs found.</p>
    <?php endif; ?>
</div>
    </div>
   
</div>
<script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this blog?");
        }
</script>
<?php
include_once '../../components/guest-footer.php';
?>