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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $isUpdated = $userModel->updateUser($userId, $name, $email);
        
        if ($isUpdated) {
            $user = $userModel->getUserByUserID($userId);
            $successMessage = "Profile updated successfully!";
        } else {
            $errorMessage = "Failed to update profile. Please try again.";
        }
    } else {
        $errorMessage = "Invalid input. Please check your details.";
    }
}

include '../../components/user-header.php';
?>

<div class="container mt-5 mb-5">
    <h1>My Profile</h1>
    <form method="POST" action='profile.php?id=<?php echo urlencode($userId);?>'>
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

<?php
include '../../components/guest-footer.php';
?>