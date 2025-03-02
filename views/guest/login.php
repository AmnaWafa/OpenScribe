
<?php 
    include_once '../../config.php';
    include_once '../../models/AuthModel.php';
    include_once '../../models/UserModel.php';
    include_once '../../components/guest-header.php';
    session_start();
    $authModel = new AuthModel(); 
    $userModel = new UserModel();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (empty($username) || empty($password)) {
            header('Location: login.php?error=Please%20fill%20in%20all%20fields');
            exit;
        }
        $user_id = $userModel->getUserID($username);
        $USER = $userModel->getUserByUserId($user_id);
        if($username==='admin' || $username==='Admin'){
            $role = 'admin'; 
            $user = $authModel->login($username, $password);
            if($user){
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;
                header('Location:../admin/dashboard.php');
            }
        }
        else{
            $role = 'user'; 
            $user = $authModel->login($username, $password);
            if($USER['status']==='disabled'){
                $_SESSION['message'] = "User is disabled by Admin. Try again later.";
                $_SESSION['message_type'] = "error";
                header('Location: login.php?error=User%Disabled');

            }else{
                if($user){
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['role'] = $role;
                    header('Location:../user/dashboard.php');
                }
                else{
                    $_SESSION['message'] = "Incorrect Password.";
                    $_SESSION['message_type'] = "error";
                    header('Location: login.php?error=Incorrect%Password');
    
                }
            } 
         
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
            <button type="submit" class="btn btn-primary my-2">Login</button>
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
    include_once '../../components/guest-footer.php'; 
    ?>