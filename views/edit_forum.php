<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'forum_db';
$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_GET['id'] ?? null;
$msg = "";

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM forums WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $forum = $result->fetch_assoc();

    if (!$forum) {
        die("Forum tidak ditemukan atau Anda tidak memiliki izin.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $stmt = $conn->prepare("UPDATE forums SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            header('Location: dashboard_pengguna.php');
            exit;
        } else {
            $msg = "Gagal mengupdate forum.";
        }
    }
} else {
    die("ID forum tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Forum</title>
</head>
<body>
<h2>Edit Forum</h2>
<form method="POST">
    <label>Judul:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($forum['title']) ?>" required><br>
    <label>Isi:</label><br>
    <textarea name="content" required><?= htmlspecialchars($forum['content']) ?></textarea><br><br>
    <button type="submit">Simpan</button>
</form>
<p><?= $msg ?></p>
<p><a href="dashboard_pengguna.php">⬅️ Kembali</a></p>
</body>
</html>
