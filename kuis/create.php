<?php
include_once '../db.php';
header('Content-Type: application/json');

/*
CREATE DATA KUIS MENGGUNAKAN METHOD GET
*/

// Ambil data dari GET
$materi_id     = $_GET['materi_id'] ?? '';
$pertanyaan    = $_GET['pertanyaan'] ?? '';
$pilihan_a     = $_GET['pilihan_a'] ?? '';
$pilihan_b     = $_GET['pilihan_b'] ?? '';
$pilihan_c     = $_GET['pilihan_c'] ?? '';
$jawaban_benar = $_GET['jawaban_benar'] ?? '';

// Validasi
if (
    empty($materi_id) ||
    empty($pertanyaan) ||
    empty($pilihan_a) ||
    empty($pilihan_b) ||
    empty($pilihan_c) ||
    empty($jawaban_benar)
) {
    echo json_encode([
        "status" => "error",
        "message" => "Parameter GET tidak lengkap"
    ]);
    exit;
}

// SQL CREATE (INSERT)
$sql = "
    INSERT INTO kuis
    (materi_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, jawaban_benar)
    VALUES (?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isssss",
    $materi_id,
    $pertanyaan,
    $pilihan_a,
    $pilihan_b,
    $pilihan_c,
    $jawaban_benar
);

// Eksekusi
if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Data kuis berhasil ditambahkan (GET)",
        "data"    => [
            "id" => $stmt->insert_id,
            "materi_id" => $materi_id
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
?>
