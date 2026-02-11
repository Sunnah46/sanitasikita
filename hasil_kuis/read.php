<?php
include '../db.php';
header("Content-Type: application/json");

// Ambil parameter
$id      = $_GET['id'] ?? null;
$user_id= $_GET['user_id'] ?? null;

$data = [];

// Base query JOIN
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

// Filter
if ($id) {
    $sql .= " WHERE hk.id = ?";
} elseif ($user_id) {
    $sql .= " WHERE hk.user_id = ?";
}

$stmt = $conn->prepare($sql);

// Bind param jika ada
if ($id) {
    $stmt->bind_param("i", $id);
} elseif ($user_id) {
    $stmt->bind_param("i", $user_id);
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
