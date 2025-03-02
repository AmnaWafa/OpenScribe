<?php 
include_once '../../config.php';
include_once '../../models/UserModel.php';
include_once '../../models/BlogModel.php';

session_start();

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
$username = $userModel->getUsernameByUserID($userId); 
$userBlogs = $userModel->getPublishedBlogs($username);
$userDrafts = $userModel->getPendingBlogs($username);

include_once '../../components/user-header.php';
?>
<div class="dashboard-container">
    <div class="w-100 d-flex py-2 justify-content-between welcome-container">
        <h2 class="">Welcome <?php echo htmlspecialchars($username);?>!</h2>  
        <a href="../../logic/write-blog.php"><button class="btn btn-primary">Write a blog</button></a>
    </div>
    <div class="blog-container">
    <div class="d-flex flex-column px-4 py-2 bg-light card">

    <h3>Your Published Blogs</h3>
        <div class="blog-container">
        <?php if (!empty($userBlogs) && is_array($userBlogs)): ?>
            <?php foreach ($userBlogs as $userBlog): ?>
                <a href="../../logic/view-blog.php?id=<?php echo urlencode($userBlog['id']); ?>" class="text-decoration-none  w-100">
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
        </div>
        <div class="d-flex flex-column px-4 py-2 bg-light card">
    <h3>Your Drafts</h3>
    <div class="blog-container">
        <?php if (!empty($userDrafts) && is_array($userDrafts)): ?>
            <?php foreach ($userDrafts as $userDraft): ?>
                <a href="../../logic/view-blog.php?id=<?php echo urlencode($userDraft['id']); ?>" class="text-decoration-none">
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
        </div>
        </div>
    <div class="community">
    <h3 class="text-center">Community</h3>
    <div class="blog-container community">
        <?php if (!empty($blogs) && is_array($blogs)): ?>
            <?php foreach ($blogs as $blog): ?>
                <a href="../../logic/view-blog.php?id=<?php echo urlencode($blog['id']); ?>" class="flex-fill text-decoration-none">
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
    </div>
</div>
<script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this blog?");
        }
</script>
<?php include_once '../../components/guest-footer.php'; ?>