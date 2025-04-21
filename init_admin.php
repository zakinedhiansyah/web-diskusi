<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "forum_db");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek apakah admin sudah ada
$cek = $koneksi->query("SELECT * FROM users WHERE username='admin'");
if ($cek->num_rows > 0) {
    echo "Admin sudah ada.\n";
    exit;
}

// Hash password
$password = password_hash("admin123", PASSWORD_DEFAULT);

// Insert admin user
$sql = "INSERT INTO users (username, password, role) VALUES ('admin', '$password', 'admin')";

if ($koneksi->query($sql) === TRUE) {
    echo "Admin berhasil dibuat!\n";
} else {
    echo "Gagal membuat admin: " . $koneksi->error . "\n";
}

$koneksi->close();
?>
