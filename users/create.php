<?php
include '../db.php';
header('Content-Type: application/json');

// Ambil data dari GET
$nama     = $_GET['nama'] ?? null;
$email    = $_GET['email'] ?? null;
$password = $_GET['password'] ?? null;
$level    = $_GET['level'] ?? 'user'; // default
$poin     = $_GET['poin'] ?? 0;

// Validasi data wajib
if (!$nama || !$email || !$password) {
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak lengkap (nama, email, password wajib diisi)"
    ]);
    exit;
}

// Hash password (WAJIB demi keamanan)
$hash = password_hash($password, PASSWORD_DEFAULT);

// Prepare insert
$stmt = $conn->prepare("INSERT INTO users (nama, email, password, level, poin) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nama, $email, $hash, $level, $poin);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "User berhasil ditambahkan",
        "data" => [
            "id" => $stmt->insert_id,
            "nama" => $nama,
            "email" => $email,
            "level" => $level,
            "poin" => $poin
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
