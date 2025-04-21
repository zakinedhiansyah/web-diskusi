<?php
// File: banned_pengguna.php
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
        // Update status banned menjadi TRUE
        $stmt = $conn->prepare("UPDATE users SET is_banned = TRUE WHERE username = ?");
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            $msg = "Pengguna berhasil dibanned.";
        } else {
            $msg = "Gagal membanned pengguna.";
        }
    } else {
        $msg = "Harap isi username.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Banned Pengguna</title>
</head>
<body>
<h2>Banned Pengguna</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <button type="submit">Banned Pengguna</button>
</form>
<p><?= $msg ?></p>
<a href="dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
