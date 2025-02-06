
<?php 
    include_once '../../models/AuthModel.php';
    include_once '../../components/guest-header.php';
    session_start();
    $authModel = new AuthModel();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (empty($username) || empty($password)) {
            header('Location: login.php?error=Please%20fill%20in%20all%20fields');
            exit;
        }
        if($username==='admin' || $username==='Admin'){
            $role = 'admin'; 
            $username = $authModel->login($username, $password);
            header('Location: ../admin/manage-users.php');
        }
        else{
            $role = 'user'; 
            $username = $authModel->login($username, $password);
            header('Location: ../user/dashboard.php');
        }
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center p-2">Login to your Account</h2>
        <form action="login.php" method="post">
            <div class="form-group py-2">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group py-2">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark my-2">Login</button>
        </form>
    </div>
    <?php
    include_once '../../components/guest-footer.php'; 
    ?>