<?php
$host = "localhost";
$db   = "sanitasikita";   // ganti jika nama database berbeda
$user = "root";
$pass = "";              // kosong jika pakai Laragon default

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal: " . $e->getMessage()
    ]));
}
