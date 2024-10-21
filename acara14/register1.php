<?php
// User.php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($email, $password, $fullname) {
        $level = 2; // Assuming regular users get level 2
        $stmt = $this->pdo->prepare("INSERT INTO user_detail (user_email, user_password, user_fullname, level) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$email, $password, $fullname, $level]);
    }
}
?>

<?php
// koneksi.php
$host = 'localhost';
$dbname = 'db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<?php
// register1.php
session_start();
require_once 'koneksi.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];

    $user = new User($pdo);

    if ($user->register($email, $password, $fullname)) {
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
    <title>Register</title>
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
                <input type="text" name="fullname" placeholder="Full Name" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
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
