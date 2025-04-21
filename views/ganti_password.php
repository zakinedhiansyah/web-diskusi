<?php
// File: ganti_password.php
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
    $newPassword = $_POST['new_password'];

    if ($username && $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param('ss', $hashed, $username);

        if ($stmt->execute()) {
            $msg = "Password berhasil diperbarui.";
        } else {
            $msg = "Gagal memperbarui password.";
        }
    } else {
        $msg = "Harap isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password Pengguna</title>
</head>
<body>
<h2>Ganti Password Pengguna</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Password Baru:</label><br>
    <input type="password" name="new_password" required><br><br>
    <button type="submit">Ganti Password</button>
</form>
<p><?= $msg ?></p>
<a href="dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
