<?php
// File: dashboard_admin.php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'forum_db';
$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Mengambil daftar forum beserta username pembuatnya
$query = "SELECT forums.*, users.username 
          FROM forums 
          JOIN users ON forums.user_id = users.id";
$result = $conn->query($query);

// Mengambil daftar pengguna
$query_users = "SELECT * FROM users";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
<h2>Selamat datang, <?= htmlspecialchars($username) ?> (Admin)</h2>

<h3>🏠 Dashboard Admin</h3>

<h4>📋 Pengguna Web</h4>
<ul>
    <li><a href="tambah_pengguna.php">📄 Tambah Pengguna</a></li>
    <li><a href="ganti_password.php">✏️ Ganti Password</a></li>
    <li><a href="hapus_pengguna.php">🗑️ Hapus Pengguna</a></li>
    <li><a href="banned_pengguna.php">⛔ Banned Pengguna</a></li>
</ul>

<h4>📋 Forum</h4>
<ul>
    <li><a href="tutup_forum.php">🚫 Tutup Forum</a></li>
    <li><a href="hapus_forum.php">🗑️ Hapus Forum</a></li>
</ul>

<h4>Daftar Forum</h4>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Judul Forum</th>
        <th>Deskripsi</th>
        <th>Created at</th>
        <th>Aksi</th>
    </tr>
    <?php while ($forum = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $forum['id'] ?></td>
            <td><?= $forum['username'] ?></td>
            <td><?= $forum['title'] ?></td>
            <td><?= $forum['content'] ?></td>
            <td><?= $forum['created_at'] ?></td>

            <td>
                <a href="edit_forum.php?id=<?= $forum['id'] ?>">✏️ Edit</a> |
                <a href="hapus_forum.php?id=<?= $forum['id'] ?>">🗑️ Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<h4>Daftar Pengguna</h4>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>
    <?php while ($user = $result_users->fetch_assoc()): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <a href="hapus_pengguna.php?id=<?= $user['id'] ?>">🗑️ Hapus</a> |
                <a href="banned_pengguna.php?id=<?= $user['id'] ?>">⛔ Banned</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<p><a href="logout.php">🔓 Logout</a></p>
</body>
</html>
