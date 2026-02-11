<?php
include '../db.php';
header('Content-Type: application/json');

/*
=================================================
BISA MENERIMA POST ATAU GET
=================================================
*/

$id         = $_POST['id'] ?? $_GET['id'] ?? null;
$judul      = $_POST['judul'] ?? $_GET['judul'] ?? null;
$deskripsi  = $_POST['deskripsi'] ?? $_GET['deskripsi'] ?? null;
$kategori   = $_POST['kategori'] ?? $_GET['kategori'] ?? null;
$video_url  = $_POST['video_url'] ?? $_GET['video_url'] ?? null;

if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "ID wajib dikirim"
    ]);
    exit;
}

/*
=================================================
1️⃣ UPDATE (HANYA JIKA FIELD DIKIRIM)
=================================================
*/
if ($judul !== null || $deskripsi !== null || $kategori !== null || $video_url !== null) {

    $update = $conn->prepare("
        UPDATE materi
        SET judul = ?, deskripsi = ?, kategori = ?, video_url = ?
        WHERE id = ?
    ");

    $update->bind_param("ssssi", $judul, $deskripsi, $kategori, $video_url, $id);
    $update->execute();
}

/*
=================================================
2️⃣ JOIN MATERI + KUIS
=================================================
*/
$join = $conn->prepare("
    SELECT 
        m.id,
        m.judul,
        m.deskripsi,
        m.kategori,
        m.video_url,
        m.created_at,
        k.id AS kuis_id,
        k.pertanyaan,
        k.pilihan_a,
        k.pilihan_b,
        k.pilihan_c,
        k.jawaban_benar
    FROM materi m
    LEFT JOIN kuis k ON m.id = k.materi_id
    WHERE m.id = ?
");

$join->bind_param("i", $id);
$join->execute();
$result = $join->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "total_data" => count($data),
    "data" => $data
]);

$conn->close();
?>
