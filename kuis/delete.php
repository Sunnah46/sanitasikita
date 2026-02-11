<?php
include '../db.php';
header('Content-Type: application/json');

// Ambil ID dari GET atau POST
$id = $_GET['id'] ?? $_POST['id'] ?? null;

// Validasi ID
if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "ID wajib dikirim"
    ]);
    exit;
}

// Jalankan DELETE
$del = $conn->prepare("DELETE FROM kuis WHERE id = ?");
$del->bind_param("i", $id);
$del->execute();

// Cek hasil
if ($del->affected_rows > 0) {
    // Data benar-benar terhapus
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil dihapus",
        "deleted_id" => $id,
        "affected_rows" => $del->affected_rows
    ]);
} else {
    // Data sudah tidak ada → tetap sukses (idempotent)
    echo json_encode([
        "status" => "success",
        "message" => "Data sudah tidak ada atau sudah dihapus sebelumnya",
        "deleted_id" => $id,
        "affected_rows" => 0
    ]);
}

$del->close();
$conn->close();
?>
