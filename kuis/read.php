<?php
// Koneksi ke database
include '../db.php';

// Response JSON
header('Content-Type: application/json');

// Array untuk menampung data
$data = [];

/*
Kolom tabel kuis:
id, materi_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, jawaban_benar
*/

// Cek apakah ada parameter GET
if (isset($_GET['id']) || isset($_GET['materi_id'])) {

    // Jika cari berdasarkan ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM kuis WHERE id = ?");
        $stmt->bind_param("i", $id);

    } else {
        // Jika cari berdasarkan materi_id
        $materi_id = $_GET['materi_id'];
        $stmt = $conn->prepare("SELECT * FROM kuis WHERE materi_id = ?");
        $stmt->bind_param("i", $materi_id);
    }

    // Eksekusi
    $stmt->execute();
    $result = $stmt->get_result();

    // Ambil data
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    // Jika tidak ada parameter, ambil semua data
    $sql = "SELECT * FROM kuis";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Kirim hasil
echo json_encode([
    "status"  => "success",
    "message" => count($data) > 0 ? "Data ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>
