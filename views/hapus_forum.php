<?php
// File: hapus_forum.php
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
    $forum_id = $_POST['forum_id'];

    if ($forum_id) {
        $stmt = $conn->prepare("DELETE FROM forums WHERE id = ?");
        $stmt->bind_param('i', $forum_id);

        if ($stmt->execute()) {
            $msg = "Forum berhasil dihapus.";
        } else {
            $msg = "Gagal menghapus forum.";
        }
    } else {
        $msg = "Harap isi ID Forum.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Forum</title>
</head>
<body>
<h2>Hapus Forum</h2>
<form method="POST">
    <label>ID Forum:</label><br>
    <input type="text" name="forum_id" required><br><br>
    <button type="submit">Hapus Forum</button>
</form>
<p><?= $msg ?></p>
<a href="dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
