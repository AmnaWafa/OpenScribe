<?php
    include_once "../config.php";
    session_start();
    $username = $_GET['username'];
    $status = 'disabled';

    if($username === 'admin' || $username === 'Admin'){          //admin cannot be disabled
        $status = 'Active';
    }

    $sql = "UPDATE users
            SET status = '$status'
            WHERE username = '$username' ";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            echo "User Disabled!!";
        }
    header('location: ../views/admin/manage-users.php');
?>
