<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Array untuk menyimpan data hasil query
$data = [];

try {
    // Cek apakah ada parameter GET 'materi_id' atau 'materi_id'
    // Jika ada, maka hanya ambil data spesifik berdasarkan parameter tersebut
    if (isset($_GET['materi_id']) || isset($_GET['materi_id'])) {

        // Jika parameter 'materi_id' disediakan, cari berdasarkan materi_id
        if (isset($_GET['materi_id'])) {
            $materi_id = $_GET['materi_id'];
            // Mempersiapkan statement SQL untuk mencari data mahasiswa beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.materi_id, j.pertanyaan, j.pilihan_a, j.pilihan_b, j.pilihan_c, j.jawaban_benar  
                FROM kuis m
                LEFT JOIN jurusan j ON m.materi_id = j.materi_id
                WHERE m.materi_id = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$materi_id]);
        } else {
            // Jika parameter 'materi_id' disediakan, cari berdasarkan materi_id
            $materi_id = $_GET['materi_id'];
            // Mempersiapkan statement SQL untuk mencari data kuis beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.materi_id, j.pertanyaan, j.pilihan_a, j.pilihan_b, j.pilihan_c, j.jawaban_benar 
                FROM kuis m
                LEFT JOIN jurusan j ON m.materi_id = j.materi_id
                WHERE m.id_mahasiswa = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$materi_id]);
        }

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // Jika tidak ada parameter GET, ambil semua data kuis beserta jurusan
        $stmt = $conn->prepare("
            SELECT m.*, j.materi_id, j.pertanyaan, j.pilihan_a, j.pilihan_b, j.pilihan_c, j.jawaban_benar 
            FROM kuis m
            LEFT JOIN jurusan j ON m.materi_id = j.materi_id
        ");
        $stmt->execute();

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kirimkan data dalam format JSON
    echo json_encode([
        "status"  => "success",
        "message" => count($data) > 0 ? "Data ditemukan" : "Data kosong",
        "data"    => $data
    ]);

} catch(PDOException $e) {
    // Jika eksekusi gagal, kirimkan pesan error
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage(),
        "data"    => []
    ]);
}

// Koneksi akan ditutup otomatis saat script selesai
/*
PETUNJUK UNTUK MENYESUAIKAN DENGAN SCHEMA TABEL LAIN:

Jika ingin menggunakan skema tabel yang berbeda, ubah bagian-bagian berikut:
1. Nama tabel: Ganti 'kuis' dan 'jurusan' dengan nama tabel Anda
2. Nama kolom: Ganti kolom sesuai dengan kolom pencarian di tabel Anda
3. Parameter GET: Sesuaikan dengan nama parameter yang ingin Anda gunakan untuk pencarian
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>