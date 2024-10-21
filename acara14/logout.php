<?php
// Auth.php
class Auth {
    public function logout() {
        session_start();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Menggunakan class Auth untuk logout
require_once 'Auth.php';

$auth = new Auth();
$auth->logout();
?>
