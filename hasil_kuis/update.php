<?php
include '../db.php';
header("Content-Type: application/json");

// Ambil dari PARAMS (GET) walau method POST
$id         = $_GET['id'] ?? null;
$user_id   = $_GET['user_id'] ?? null;
$materi_id = $_GET['materi_id'] ?? null;
$skor      = $_GET['skor'] ?? null;
$tanggal   = $_GET['tanggal'] ?? date("Y-m-d H:i:s");

// Validasi
if (!$id || !$user_id || !$materi_id || !$skor) {
    echo json_encode([
        "status" => "error",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

// SQL UPDATE
$stmt = $conn->prepare("
    UPDATE hasil_kuis
    SET user_id = ?, materi_id = ?, skor = ?, tanggal = ?
    WHERE id = ?
");

$stmt->bind_param("iiisi", $user_id, $materi_id, $skor, $tanggal, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Data hasil kuis berhasil diperbarui",
        "data" => [
            "id" => $id,
            "user_id" => $user_id,
            "materi_id" => $materi_id,
            "skor" => $skor,
            "tanggal" => $tanggal
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
