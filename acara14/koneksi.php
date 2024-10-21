<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'db';
    private $username = 'root';
    private $password = '';
    private $pdo;

    // Constructor untuk koneksi database
    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
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

    // Method untuk mengambil semua user
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM user_detail");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk update user
    public function updateUser($id, $email, $fullname, $level) {
        $stmt = $this->pdo->prepare("UPDATE user_detail SET user_email = ?, user_fullname = ?, level = ? WHERE id = ?");
        return $stmt->execute([$email, $fullname, $level, $id]);
    }

    // Method untuk delete user
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM user_detail WHERE id = ?");
        return $stmt->execute([$id]);
    }
}


?>
