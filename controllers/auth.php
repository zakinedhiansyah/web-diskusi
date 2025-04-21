<?php
session_start();
require '../config/db.php';

if (isset($_POST['register'])) {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
    $conn->query($sql);
    header("Location: ../views/login.php");
    exit;
}

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $result = $conn->query("SELECT * FROM users WHERE username = '$user'");
    $row = $result->fetch_assoc();

    if ($row && password_verify($pass, $row['password'])) {
        if ($row['banned']) {
            echo "Akun Anda telah dibanned.";
            exit;
        }
        $_SESSION['user'] = $row;
        header("Location: ../views/dashboard_" . $row['role'] . ".php");
    } else {
        echo "Login gagal.";
    }
}
