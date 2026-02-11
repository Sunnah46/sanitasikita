<?php
include_once '../db.php';
header('Content-Type: application/json');

// Ambil dari GET
$user_id = $_GET['user_id'] ?? null;
$kategori = $_GET['kategori'] ?? null;
$isi = $_GET['isi'] ?? null;

// Validasi
if (!$user_id || !$kategori || !$isi) {
    echo json_encode([
        "status" => "error",
        "message" => "user_id, kategori, dan isi wajib dikirim"
    ]);
    exit;
}

// Insert data
$stmt = $conn->prepare("
    INSERT INTO saran (user_id, kategori, isi)
    VALUES (?, ?, ?)
");

$stmt->bind_param("iss", $user_id, $kategori, $isi);

if ($stmt->execute()) {

    echo json_encode([
        "status"  => "success",
        "message" => "Data saran berhasil ditambahkan (GET)",
        "data"    => [
            "id" => $stmt->insert_id,
            "user_id" => $user_id,
            "kategori" => $kategori,
            "isi" => $isi
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
