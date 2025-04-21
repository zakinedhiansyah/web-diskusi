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
if ($id) {
    $stmt = $conn->prepare("DELETE FROM forums WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $id, $_SESSION['user_id']);
    $stmt->execute();
}
header('Location: dashboard_pengguna.php');
exit;
