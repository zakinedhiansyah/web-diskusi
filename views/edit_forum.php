<?php
// File: tambah_forum.php / edit_forum.php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'forum_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Jika file ini adalah edit_forum.php, lakukan fetch data lama
$isEdit = basename(__FILE__) === 'edit_forum.php';
$forum = ["title" => "", "content" => ""];

if ($isEdit && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM forums WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $forum = $result->fetch_assoc();
    } else {
        die("Forum tidak ditemukan atau tidak memiliki akses.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title !== '' && $content !== '') {
        if ($isEdit) {
            $stmt = $conn->prepare("UPDATE forums SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssii", $title, $content, $id, $_SESSION['user_id']);
        } else {
            $stmt = $conn->prepare("INSERT INTO forums (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $_SESSION['user_id'], $title, $content);
        }
        $stmt->execute();
        header('Location: dashboard_pengguna.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $isEdit ? 'Edit Forum' : 'Tambah Forum' ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .form-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        textarea {
            resize: vertical;
            height: 120px;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2><?= $isEdit ? 'Edit Forum' : 'Tambah Forum' ?></h2>
        <form method="POST">
            <label for="title">Judul</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($forum['title']) ?>" required>

            <label for="content">Isi</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($forum['content']) ?></textarea>

            <button type="submit"><?= $isEdit ? 'Perbarui Forum' : 'Tambah Forum' ?></button>
        </form>
        <a href="dashboard_pengguna.php" class="back-link">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
