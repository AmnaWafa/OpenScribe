<?php
include_once "../config.php";
include_once '../models/UserModel.php';

session_start();
$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userId'] ?? '';
    $name = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($userID) && !empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $isUpdated = $userModel->updateUser($userID, $name, $email);
        
        if ($isUpdated) {
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update profile. Please try again.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Invalid input. Please check your details.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: ../views/user/profile.php");
    exit;
}
?>
