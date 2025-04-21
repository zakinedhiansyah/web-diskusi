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
    <style>
        body {
            background: #f4f7f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background: #2ecc71;
            color: white;
            padding: 12px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #27ae60;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: #2980b9;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Pengguna Baru</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Tambah Pengguna</button>
        </form>

        <?php if ($msg): ?>
            <p class="message"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <a class="back-link" href="dashboard_admin.php">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
