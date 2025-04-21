<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$conn = new mysqli('localhost', 'root', '', 'forum_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['forum_id'])) {
    $forum_id = intval($_POST['forum_id']);
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        $stmt = $conn->prepare("INSERT INTO comments (forum_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $forum_id, $user_id, $comment);
        $stmt->execute();
    }
}

// Mengambil daftar forum lengkap dengan username
$query = "SELECT forums.*, users.username FROM forums JOIN users ON forums.user_id = users.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pengguna</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f5;
            margin: 0;
            padding: 20px;
        }
        header {
            background: #3498db;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        header h2 {
            margin: 0;
        }
        .forum-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
        }
        .forum-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .forum-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .forum-meta {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 10px;
        }
        .forum-content {
            margin-bottom: 10px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }
        button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #27ae60;
        }
        .top-links {
            margin-bottom: 15px;
        }
        .top-links a {
            text-decoration: none;
            color: #3498db;
            margin-right: 15px;
            font-weight: bold;
        }
        .top-links a:hover {
            text-decoration: underline;
        }
        .forum-actions {
            margin-top: 10px;
        }
        .forum-actions a {
            text-decoration: none;
            color: #e74c3c;
            margin-right: 10px;
        }
        .forum-actions a.edit {
            color: #f39c12;
        }
    </style>
</head>
<body>
    <header>
        <h2>Selamat datang, <?= htmlspecialchars($username) ?> (Pengguna)</h2>
        <div class="top-links">
            <a href="tambah_forum.php">➕ Tambah Forum</a>
            <a href="logout.php">🔓 Logout</a>
        </div>
    </header>

    <div class="forum-container">
        <?php while ($forum = $result->fetch_assoc()): ?>
            <div class="forum-card">
                <div class="forum-title"><?= $forum['title'] ?></div>
                <div class="forum-content"><?= $forum['content'] ?></div>
                <div class="forum-meta">Oleh: <?= $forum['username'] ?> | <?= $forum['created_at'] ?></div>

                <?php if ($forum['user_id'] == $user_id): ?>
                <div class="forum-actions">
                    <a href="edit_forum.php?id=<?= $forum['id'] ?>" class="edit">✏️ Edit</a>
                    <a href="hapus_forum.php?id=<?= $forum['id'] ?>" onclick="return confirm('Yakin ingin menghapus forum ini?');">🗑️ Hapus</a>
                </div>
                <?php endif; ?>

                <div class="forum-comments">
                    <strong>Komentar:</strong>
                    <ul style="list-style: none; padding: 0;">
                        <?php
                        $fid = $forum['id'];
                        $q_comments = "SELECT comments.comment, comments.created_at, users.username 
                                       FROM comments 
                                       JOIN users ON comments.user_id = users.id
                                       WHERE comments.forum_id = $fid 
                                       ORDER BY comments.created_at ASC";
                        $comments_result = $conn->query($q_comments);
                        while ($c = $comments_result->fetch_assoc()):
                        ?>
                            <li><strong><?= $c['username'] ?></strong>: <?= $c['comment'] ?> <em>(<?= $c['created_at'] ?>)</em></li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <form method="POST">
                    <input type="hidden" name="forum_id" value="<?= $forum['id'] ?>">
                    <textarea name="comment" rows="2" placeholder="Tulis komentar..." required></textarea>
                    <button type="submit">Kirim Komentar</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
