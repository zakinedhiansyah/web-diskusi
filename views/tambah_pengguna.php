<?php
// File: tambah_pengguna.php
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
    $password = $_POST['password'];

    if ($username && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param('ss', $username, $hashed);

        if ($stmt->execute()) {
            $msg = "Pengguna berhasil ditambahkan.";
        } else {
            $msg = "Username sudah digunakan.";
        }
    } else {
        $msg = "Harap isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pengguna</title>
</head>
<body>
<h2>Tambah Pengguna Baru</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Tambah Pengguna</button>
</form>
<p><?= $msg ?></p>
<a href="dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
