<?php
include_once '../db.php';
header('Content-Type: application/json');

$materi_id = $_GET['materi_id'] ?? null;

$sql = "
    SELECT 
        k.id AS kuis_id,
        k.pertanyaan,
        k.pilihan_a,
        k.pilihan_b,
        k.pilihan_c,
        k.jawaban_benar,
        m.id AS materi_id,
        m.judul AS judul_materi
    FROM kuis k
    JOIN materi m ON k.materi_id = m.id
";

if ($materi_id) {
    $sql .= " WHERE k.materi_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $materi_id);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "total"  => count($data),
    "data"   => $data
]);

$stmt->close();
$conn->close();
