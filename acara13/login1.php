<?php
session_start();
require_once ('koneksi.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = loginUser($email, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
<div class="wrapper">
    <form method="post" action="login1.php">
        <h1>Login</h1>
        <?php 
            if (isset($_SESSION['message'])) {
                echo "<p>{$_SESSION['message']}</p>";
                unset($_SESSION['message']);
            }
            if (isset($error)) echo "<p>$error</p>"; 
        ?>
        <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class='bx bxs-user'></i>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt'></i>
        </div>
        <div class="remember-forgot">
            <label><input type="checkbox"> Remember me</label>
            <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" class="btn">Login</button>  
    </form>
    <div class="register-link">
        <p>Don't have an account?
            <a href="./register1.php">Register</a>
        </p>
    </div>
</div>

</body>

</html>

