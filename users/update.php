<?php
header("Content-Type: application/json");
include "../db.php";

$data = $_POST;
if (empty($data)) {
    $data = $_GET;
}

$id         = $data['id'] ?? null;
$nama       = $data['nama'] ?? null;
$email      = $data['email'] ?? null;
$password   = $data['password'] ?? null;
$level      = $data['level'] ?? null;
$poin       = $data['poin'] ?? null;
$created_at = $data['created_at'] ?? null;

if (!$id || !$nama || !$email || !$password) {
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak lengkap"
    ]);
    exit;
}

$sql = "UPDATE users SET 
            nama = ?, 
            email = ?, 
            password = ?, 
            level = ?, 
            poin = ?, 
            created_at = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssissi", $nama, $email, $password, $level, $poin, $created_at, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "User berhasil diupdate"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
