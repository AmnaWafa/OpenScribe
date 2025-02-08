<?php 
include_once '../../config.php';
include_once '../../models/UserModel.php';
include_once '../../models/BlogModel.php';

session_start();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: ../guest/login.php');
    exit;
}

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

$userModel = new UserModel();
$username = $userModel->getUserByUsername($userId); 
$userBlogs = $userModel->getPublishedBlogs($username);
$userDrafts = $userModel->getPendingBlogs($username);

include_once '../../components/user-header.php';
?>
<div>
    <h2>Welcome <?php echo htmlspecialchars($username);?>!</h2>  
    <a href="../../logic/write-blog.php"><button>Write a blog</button></a>
</div>
<h3>Your Published Blogs</h3>
    <div class="blog-container col-md-4">
    <?php if (!empty($userBlogs) && is_array($userBlogs)): ?>
        <?php foreach ($userBlogs as $userBlog): ?>
            <a href="../../logic/view-blog.php?id=<?php echo urlencode($userBlog['id']); ?>">
                <div class="blog-card card card-body">
                    <p><?php echo htmlspecialchars($userBlog['username']); ?></p>
                    <h3 class="card-title"><?php echo nl2br(htmlspecialchars($userBlog['title'])); ?></h3>
                    <p class="timestamp"><?php echo date("F j, Y, g:i a", strtotime($userBlog['created_at'])); ?></p>
                    <form method="POST" onsubmit="return confirmDelete();">
                        <input type="hidden" name="blog_id" value="<?= htmlspecialchars($userBlog['id']) ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No blogs found.</p>
    <?php endif; ?>
</div>
<h3>Your Drafts</h3>
<div class="blog-container col-md-4">
    <?php if (!empty($userDrafts) && is_array($userDrafts)): ?>
        <?php foreach ($userDrafts as $userDraft): ?>
            <a href="../../logic/view-blog.php?id=<?php echo urlencode($userDraft['id']); ?>">
                <div class="blog-card card card-body">
                    <p><?php echo htmlspecialchars($userDraft['username']); ?></p>
                    <h3 class="card-title"><?php echo nl2br(htmlspecialchars($userDraft['title'])); ?></h3>
                    <p class="timestamp"><?php echo date("F j, Y, g:i a", strtotime($userDraft['created_at'])); ?></p>
                    <form method="POST" onsubmit="return confirmDelete();">
                        <input type="hidden" name="blog_id" value="<?= htmlspecialchars($userDraft['id']) ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No blogs found.</p>
    <?php endif; ?>
</div>
<h3>Community</h3>
<div class="blog-container col-md-4">
    <?php if (!empty($blogs) && is_array($blogs)): ?>
        <?php foreach ($blogs as $blog): ?>
            <a href="../../logic/view-blog.php?id=<?php echo urlencode($blog['id']); ?>">
                <div class="blog-card card card-body">
                    <p><?php echo htmlspecialchars($blog['username']); ?></p>
                    <h3 class="card-title"><?php echo nl2br(htmlspecialchars($blog['title'])); ?></h3>
                    <p class="timestamp"><?php echo date("F j, Y, g:i a", strtotime($blog['created_at'])); ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No blogs found.</p>
    <?php endif; ?>
</div>
<script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this blog?");
        }
</script>
<?php include_once '../../components/guest-footer.php'; ?>