<?php
include '../db.php';
header('Content-Type: application/json');

/*
UPDATE DATA KUIS (METHOD GET)
*/

// Validasi ID
if (!isset($_GET['id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "ID wajib dikirim"
    ]);
    exit;
}

// Ambil data dari GET
$id            = $_GET['id'];
$pertanyaan    = $_GET['pertanyaan'] ?? '';
$pilihan_a     = $_GET['pilihan_a'] ?? '';
$pilihan_b     = $_GET['pilihan_b'] ?? '';
$pilihan_c     = $_GET['pilihan_c'] ?? '';
$jawaban_benar = $_GET['jawaban_benar'] ?? '';

// Validasi
if (
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

// SQL UPDATE
$sql = "
UPDATE kuis
SET
    pertanyaan = ?,
    pilihan_a = ?,
    pilihan_b = ?,
    pilihan_c = ?,
    jawaban_benar = ?
WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssi",
    $pertanyaan,
    $pilihan_a,
    $pilihan_b,
    $pilihan_c,
    $jawaban_benar,
    $id
);

// Eksekusi
if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Data kuis berhasil diupdate",
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
