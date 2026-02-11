<?php
include '../db.php';
header("Content-Type: application/json");

// Ambil ID dari POST
$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "ID wajib dikirim"
    ]);
    exit;
}

// Hapus data
$stmt = $conn->prepare("DELETE FROM hasil_kuis WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Apapun hasilnya, anggap sukses
echo json_encode([
    "status" => "success",
    "message" => "Permintaan delete berhasil diproses"
]);
