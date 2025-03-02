<?php 
include_once '../../config.php';
include_once '../../models/AuthModel.php';
include_once '../../components/guest-header.php';
session_start();

$authModel = new AuthModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['message_type'] = "error";
        header('Location: register.php');
        exit;
    }

    // Check if username already exists
    if ($authModel->checkUsername($username)) {
        $_SESSION['message'] = "Username already exists. Please choose a different one.";
        $_SESSION['message_type'] = "error";
        header('Location: register.php');
        exit;
    }

    $role = ($username === 'admin' || $username === 'Admin') ? 'admin' : 'user';
    $registered = $authModel->register($username, $email, $password, $role);

    if ($registered) {
        $_SESSION['message'] = "Registration successful! You can now log in.";
        $_SESSION['message_type'] = "success";
        header('Location: ../user/dashboard.php'); 
    } else {
        $_SESSION['message'] = "Registration failed. Please try again.";
        $_SESSION['message_type'] = "error";
        header('Location: register.php');
    }
    exit;
}
?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo ($_SESSION['message_type'] === 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
<?php endif; ?>

<div class="container mt-5">
    <h2 class="text-center p-2">Register</h2>
    <form action="register.php" method="post" onsubmit="return validateForm()">
        <div class="form-group py-2">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group py-2">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group py-2">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary my-2">Register</button>
    </form>
</div>

<script>
    function validateForm() {
        const password = document.getElementById("password").value;
        
        if (password.length < 8) {
            alert("Password must be at least 8 characters long.");
            return false;
        }
        
        return true;
    }
</script>



<?php include_once '../../components/guest-footer.php'; ?>
