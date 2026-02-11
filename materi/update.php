<?php
include '../db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$judul = $_POST['judul'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$video_url = $_POST['video_url'] ?? null;

if (!$id) {
    echo json_encode(["status"=>"error","message"=>"ID wajib dikirim"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE materi
    SET judul = ?, deskripsi = ?, kategori = ?, video_url = ?
    WHERE id = ?
");
$stmt->bind_param("ssssi", $judul, $deskripsi, $kategori, $video_url, $id);

$stmt->execute();

echo json_encode([
    "status" => "success",
    "affected_rows" => $stmt->affected_rows
]);

$stmt->close();
$conn->close();
