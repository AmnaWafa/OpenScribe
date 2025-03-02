<?php

include_once '../../models/UserModel.php';

session_start();
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: ../../index.php');
    exit;
}

$userModel = new UserModel();
$user = $userModel->getUserByUserID($userId);

include '../../components/user-header.php';
?>

<div class="container mt-5 mb-5">
    <h1>My Profile</h1>
    <form method="post" action="../../logic/update.php">
    <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user['id']); ?>">
    <div class="mb-3">
        <label for="username" class="form-label">Name</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Update Profile</button>
</form>

</div>
<?php if (isset($_SESSION['message'])): ?>
    <script>
        alert("<?php echo $_SESSION['message']; ?>");
    </script>
    <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
<?php endif; ?>

<?php
include '../../components/guest-footer.php';
?>