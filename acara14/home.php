<?php
session_start();
require_once 'koneksi.php';

class UserManagement {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method untuk mengambil semua pengguna
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM user_detail");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk menghapus pengguna
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM user_detail WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Method untuk mengupdate pengguna
    public function updateUser($id, $email, $fullname, $level) {
        $stmt = $this->pdo->prepare("UPDATE user_detail SET user_email = ?, user_fullname = ?, level = ? WHERE id = ?");
        return $stmt->execute([$email, $fullname, $level, $id]);
    }

    // Method untuk mendapatkan detail pengguna tertentu
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_detail WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Inisialisasi koneksi ke database dan UserManagement
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $userManager = new UserManagement($pdo);

    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }

    $user = $_SESSION['user'];

    // Handle delete action
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $userManager->deleteUser($_GET['id']);
        header("Location: home.php"); // Redirect after delete
        exit();
    }

    // Handle update action
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = $_POST['id'];
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $level = $_POST['level'];
        $userManager->updateUser($id, $email, $fullname, $level);
        header("Location: home.php"); // Redirect after update
        exit();
    }

    $users = $userManager->getAllUsers();

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['user_fullname']); ?>!</h2>
    <p>Your email: <?php echo htmlspecialchars($user['user_email']); ?></p>
    <p>Your level: <?php echo $user['level']; ?></p>
    
    <h3>User List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Full Name</th>
            <th>Level</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo htmlspecialchars($u['id']); ?></td>
            <td><?php echo htmlspecialchars($u['user_email']); ?></td>
            <td><?php echo htmlspecialchars($u['user_fullname']); ?></td>
            <td><?php echo $u['level']; ?></td>
            <td>
                <a href="?action=edit&id=<?php echo $u['id']; ?>">Edit</a>
                <a href="?action=delete&id=<?php echo $u['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])): ?>
        <?php
        $editUser = $userManager->getUserById($_GET['id']);
        ?>
        <h3>Edit User</h3>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
            <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($editUser['user_email']); ?>" required></label><br>
            <label>Full Name: <input type="text" name="fullname" value="<?php echo htmlspecialchars($editUser['user_fullname']); ?>" required></label><br>
            <label>Level: <input type="number" name="level" value="<?php echo $editUser['level']; ?>" required></label><br>
            <input type="submit" value="Update">
        </form>
    <?php endif; ?>

    <br>
    <a href="login1.php">Logout</a>
</body>
</html>
