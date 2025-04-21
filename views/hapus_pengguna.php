<?php
// File: hapus_pengguna.php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'forum_db';
$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    if ($username) {
        $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            $msg = "Pengguna berhasil dihapus.";
        } else {
            $msg = "Gagal menghapus pengguna.";
        }
    } else {
        $msg = "Harap isi username.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Pengguna</title>
</head>
<body>
<h2>Hapus Pengguna</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <button type="submit">Hapus Pengguna</button>
</form>
<p><?= $msg ?></p>
<a href="dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
