<?php
include_once '../db.php';
header('Content-Type: application/json');

// Ambil dari POST
$id       = $_POST['id'] ?? null;
$user_id  = $_POST['user_id'] ?? null;
$kategori = $_POST['kategori'] ?? null;
$isi      = $_POST['isi'] ?? null;

// Validasi
if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "ID wajib dikirim"
    ]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE saran 
    SET user_id = ?, kategori = ?, isi = ?
    WHERE id = ?
");

$stmt->bind_param("issi", $user_id, $kategori, $isi, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil diupdate",
        "affected_rows" => $stmt->affected_rows
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
