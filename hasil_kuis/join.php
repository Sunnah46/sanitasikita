<?php
include '../db.php';
header("Content-Type: application/json");

// Ambil dari BODY (POST)
$user_id = $_POST['user_id'] ?? null;
$materi_id = $_POST['materi_id'] ?? null;

$data = [];

// Query dasar JOIN
$sql = "
SELECT 
    hk.id,
    u.nama,
    u.email,
    m.judul AS materi,
    hk.skor,
    hk.tanggal
FROM hasil_kuis hk
JOIN users u ON hk.user_id = u.id
JOIN materi m ON hk.materi_id = m.id
";

// Filter dinamis
if ($user_id && $materi_id) {
    $sql .= " WHERE hk.user_id = ? AND hk.materi_id = ?";
} elseif ($user_id) {
    $sql .= " WHERE hk.user_id = ?";
} elseif ($materi_id) {
    $sql .= " WHERE hk.materi_id = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameter sesuai kondisi
if ($user_id && $materi_id) {
    $stmt->bind_param("ii", $user_id, $materi_id);
} elseif ($user_id) {
    $stmt->bind_param("i", $user_id);
} elseif ($materi_id) {
    $stmt->bind_param("i", $materi_id);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "message" => count($data) > 0 ? "Data ditemukan" : "Data kosong",
    "data" => $data
]);
