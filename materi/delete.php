<?php
include '../db.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$id = $_POST['id'] ?? $input['id'] ?? null;

if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "ID wajib dikirim"
    ]);
    exit;
}

/* 
CEK: apakah materi dipakai di kuis
*/
$cekKuis = $conn->prepare("SELECT COUNT(*) AS total FROM kuis WHERE materi_id = ?");
$cekKuis->bind_param("i", $id);
$cekKuis->execute();
$res1 = $cekKuis->get_result()->fetch_assoc();

/*
CEK: apakah materi dipakai di hasil_kuis
*/
$cekHasil = $conn->prepare("SELECT COUNT(*) AS total FROM hasil_kuis WHERE materi_id = ?");
$cekHasil->bind_param("i", $id);
$cekHasil->execute();
$res2 = $cekHasil->get_result()->fetch_assoc();

if ($res1['total'] > 0 || $res2['total'] > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Materi tidak bisa dihapus karena masih digunakan oleh kuis atau hasil kuis"
    ]);
    exit;
}

/* 
Jika aman → hapus
*/
$del = $conn->prepare("DELETE FROM materi WHERE id = ?");
$del->bind_param("i", $id);
$del->execute();

echo json_encode([
    "status" => "success",
    "message" => "Materi berhasil dihapus",
    "deleted_id" => $id
]);

$del->close();
$conn->close();
