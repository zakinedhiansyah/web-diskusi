<?php
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

// Hapus forum jika ada post hapus_forum_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_forum_id'])) {
    $hapus_forum_id = intval($_POST['hapus_forum_id']);
    $conn->query("DELETE FROM forums WHERE id = $hapus_forum_id");
}

// Hapus user jika ada post hapus_user_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_user_id'])) {
    $hapus_user_id = intval($_POST['hapus_user_id']);
    $conn->query("DELETE FROM users WHERE id = $hapus_user_id");
}

// Ambil ulang data setelah kemungkinan perubahan
$query = "SELECT forums.*, users.username FROM forums JOIN users ON forums.user_id = users.id";
$result = $conn->query($query);

$query_users = "SELECT * FROM users";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fa;
            padding: 20px;
            margin: 0;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        h2 {
            margin: 0;
            font-size: 2rem;
        }
        .section {
            margin-bottom: 40px;
        }
        h3, h4 {
            color: #34495e;
        }
        .actions {
            margin: 10px 0;
        }
        .actions a {
            text-decoration: none;
            margin-right: 10px;
            color: #2980b9;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f0f8ff;
        }
        .btn-logout {
            margin-top: 30px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-logout:hover {
            background-color: #c0392b;
        }
        form.inline {
            display: inline;
        }
        button {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
        }
        button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h2>Selamat datang, <?= htmlspecialchars($username) ?> (Admin)</h2>
    </header>

    <div class="section">
        <h3>🏠 Dashboard Admin</h3>
        <h4>📋 Pengguna Web</h4>
        <div class="actions">
            <a href="tambah_pengguna.php">📄 Tambah Pengguna</a>
            <a href="ganti_password.php">✏️ Ganti Password</a>
        </div>
    </div>

    <div class="section">
        <h4>📑 Daftar Forum</h4>
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Judul Forum</th>
                <th>Deskripsi</th>
                <th>Created at</th>
                <th>Actions</th>
            </tr>
            <?php $i = 1; while ($forum = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $forum['username'] ?></td>
                    <td><?= $forum['title'] ?></td>
                    <td><?= $forum['content'] ?></td>
                    <td><?= $forum['created_at'] ?></td>
                    <td>
                        <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus forum ini?');">
                            <input type="hidden" name="hapus_forum_id" value="<?= $forum['id'] ?>">
                            <button type="submit">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="section">
        <h4>👤 Daftar Pengguna</h4>
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php $i = 1; while ($user = $result_users->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                            <input type="hidden" name="hapus_user_id" value="<?= $user['id'] ?>">
                            <button type="submit">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <a href="logout.php" class="btn-logout">🔓 Logout</a>
</body>
</html>
