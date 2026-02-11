<?php
include_once '../db.php';
header('Content-Type: application/json');

// Ambil data dari GET
$judul     = $_GET['judul'] ?? null;
$deskripsi = $_GET['deskripsi'] ?? null;
$kategori  = $_GET['kategori'] ?? null;

if (!$judul || !$deskripsi || !$kategori) {
    echo json_encode([
        "status" => "error",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO materi (judul, deskripsi, kategori) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $judul, $deskripsi, $kategori);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Materi berhasil ditambahkan",
        "id" => $stmt->insert_id
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
