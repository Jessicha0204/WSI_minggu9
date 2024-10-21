<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];

    if (registerUser($email, $password, $fullname)) {
        $_SESSION['message'] = "Registration successful. Please log in.";
        header("Location: login1.php");
        exit();
    } else {
        $error = "Registration failed. Please try again.";
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
            <h1>Register</h1>
            
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
            <form method="post" action="register1.php">
            <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
            <input type="text" name="fullname" placeholder="FullName" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me
                </label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Registrasi</button>
</form>
            <div class="register-link">
                <p>Have an account?
                    <a href="./login1.php">Login</a>
                </p>
            </div>
    </div>
</body>

</html>
