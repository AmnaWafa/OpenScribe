<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: ../guest/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.16/css/froala_editor.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.16/js/froala_editor.min.js"></script>
  <title>Write Blog</title>
</head>
<body>
  <h1>Write Your Blog</h1>
  <form action='save-blog.php?id=<?php echo urlencode($userId);?>' method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title">
    <!-- Froala Editor Container -->
    <div id="froala-editor"></div>
    <!-- Hidden input to store Froala's content -->
    <input type="hidden" id="contentInput" name="content">
    <button type="submit">Save Draft</button>
  </form>

  <script>
    // Initialize Froala Editor and capture the content
    const editor = new FroalaEditor("#froala-editor", {
      events: {
        'contentChanged': function () {
          const content = editor.html.get(); // Get the HTML content
          document.getElementById('contentInput').value = content; // Save to hidden input
        }
      }
    });
  </script>
</body>
</html>
