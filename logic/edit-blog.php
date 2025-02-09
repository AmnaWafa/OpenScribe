<?php
include_once '../components/user-header.php';
include_once '../config.php';
include_once '../models/BlogModel.php';
session_start();
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

$blogModel = new BlogModel();
$blog = $blogModel->getBlogById($blogId);

if (!$blog) {
    echo "Blog not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@4.0.10/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/froala-editor@4.0.10/js/froala_editor.pkgd.min.js"></script>
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
</head>
<body>
<div class="container mt-5">
<h1>Edit Your Blog</h1>
  <form action='save-blog.php?id=<?php echo urlencode($userId);?>' method="POST">
    <div class="form-group py-2 m-2">
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
    </div>
    
    <div id="froala-editor"><?php echo htmlspecialchars_decode($blog['content']); ?></div>
    <input type="hidden" id="contentInput" name="content">
    <button type="submit" class="btn btn-primary my-2">Save Draft</button>
  </form>
</div>
<script>
    const editor = new FroalaEditor("#froala-editor", {
  events: {
    'initialized': function () {
      // Capture the initial content
      const content = editor.html.get();
      document.getElementById('contentInput').value = content;
    },
    'contentChanged': function () {
      // Update hidden input on content change
      const content = editor.html.get();
      document.getElementById('contentInput').value = content;
    }
  }
});
</script>
    <?php include_once '../components/guest-footer.php' ?>
</body>
</html>
