<?php
    include_once '../models/AuthModel.php';
    $authModel = new AuthModel();
    $authModel->logout();
    header("Location: ../views/guest/home.php");
    exit;
?>