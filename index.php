<?php
function redirect($url) {
    header("Location: $url");
    exit();
}
session_start();
include_once 'config.php';

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            redirect('/views/admin/dashboard.php');
            break;
        case 'user':
            redirect('/views/user/dashboard.php');
            break;
    }
}
redirect('/blog-app/views/guest/home.php');