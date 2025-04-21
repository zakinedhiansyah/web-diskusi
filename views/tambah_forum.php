<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'forum_db';
$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $status = 'open';

    $stmt = $conn->prepare("INSERT INTO forums (title, content, user_id, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $content, $user_id, $status);

    if ($stmt->execute()) {
        header("Location: dashboard_pengguna.php");
        exit;
    } else {
        $msg = "Gagal membuat forum.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Forum</title>
</head>
<body>
    <h2>📄 Tambah Forum Baru</h2>
    <form method="POST">
        <label>Judul Forum:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Isi Forum:</label><br>
        <textarea name="content" rows="5" cols="30" required></textarea><br><br>

        <button type="submit">Tambah Forum</button>
    </form>
    <p style="color: green"><?= $msg ?></p>
    <p><a href="dashboard_pengguna.php">⬅️ Kembali ke Dashboard</a></p>
</body>
</html>
