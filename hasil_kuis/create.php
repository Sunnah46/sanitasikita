<?php
include '../db.php';
header("Content-Type: application/json");

// Ambil data dari GET atau POST
$data = $_GET;
if (empty($data)) {
    $data = $_POST;
}

$user_id   = $data['user_id'] ?? null;
$materi_id = $data['materi_id'] ?? null;
$skor      = $data['skor'] ?? null;
$tanggal   = $data['tanggal'] ?? date("Y-m-d H:i:s");

// Validasi
if (!$user_id || !$materi_id || !$skor) {
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak lengkap"
    ]);
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("
    INSERT INTO hasil_kuis (user_id, materi_id, skor, tanggal)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("iiis", $user_id, $materi_id, $skor, $tanggal);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Skor berhasil disimpan",
        "id" => $conn->insert_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
