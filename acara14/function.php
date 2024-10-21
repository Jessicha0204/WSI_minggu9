<?php
class User {
    private $pdo;

    // Constructor untuk koneksi database
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method untuk register user
    public function registerUser($email, $password, $fullname) {
        $level = 2; // default level
        $stmt = $this->pdo->prepare("INSERT INTO user_detail (user_email, user_password, user_fullname, level) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$email, $password, $fullname, $level]);
    }

    // Method untuk login user
    public function loginUser($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_detail WHERE user_email = ? AND user_password = ?");
        $stmt->execute([$email, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Contoh penggunaan
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user = new User($pdo);

    // Registrasi user baru
    if ($user->registerUser('example@example.com', 'password123', 'John Doe')) {
        echo "User registered successfully.";
    }

    // Login user
    $loggedInUser = $user->loginUser('example@example.com', 'password123');
    if ($loggedInUser) {
        echo "Welcome, " . $loggedInUser['user_fullname'];
    }
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
