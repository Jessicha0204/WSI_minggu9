<?php
session_start();
require_once 'koneksi.php';

class UserAuth {
    private $pdo;

    // Constructor untuk menginisialisasi PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method untuk login user
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_detail WHERE user_email = ? AND user_password = ?");
        $stmt->execute([$email, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk menyimpan session user
    public function setSession($user) {
        $_SESSION['user'] = $user;
    }

    // Method untuk mengecek apakah user sudah login
    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    // Method untuk menghapus session user
    public function logout() {
        session_destroy();
    }

    // Method untuk menampilkan pesan error
    public function showError($error) {
        return "<p>$error</p>";
    }
}

// Inisialisasi koneksi ke database dan class UserAuth
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $auth = new UserAuth($pdo);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $auth->login($email, $password);
        if ($user) {
            $auth->setSession($user);
            header("Location: home.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
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
            if (isset($error)) echo $auth->showError($error); 
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
