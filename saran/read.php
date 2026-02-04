<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Array untuk menyimpan data hasil query
$data = [];

try {
    // Cek apakah ada parameter GET 'user_id' atau 'id'
    // Jika ada, maka hanya ambil data spesifik berdasarkan parameter tersebut
    if (isset($_GET['user_id']) || isset($_GET['id'])) {

        // Jika parameter 'user_id' disediakan, cari berdasarkan user_id
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            // Mempersiapkan statement SQL untuk mencari data mahasiswa beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.user_id, j.kategori, j.isi, j.tanggal
                FROM saran m
                LEFT JOIN jurusan j ON m.user_id = j.user_id
                WHERE m.user_id = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$user_id]);
        } else {
            // Jika parameter 'id' disediakan, cari berdasarkan id
            $id = $_GET['id'];
            // Mempersiapkan statement SQL untuk mencari data saran beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.user_id, j.kategori, j.isi, j.tanggal, 
                FROM saran m
                LEFT JOIN jurusan j ON m.user_id = j.user_id
                WHERE m.id_mahasiswa = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$id]);
        }

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // Jika tidak ada parameter GET, ambil semua data saran beserta jurusan
        $stmt = $conn->prepare("
            SELECT m.*, j.user_id, j.kategori, j.isi, j.tanggal, 
            FROM saran m
            LEFT JOIN jurusan j ON m.user_id = j.user_id
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
1. Nama tabel: Ganti 'saran' dan 'jurusan' dengan nama tabel Anda
2. Nama kolom: Ganti kolom sesuai dengan kolom pencarian di tabel Anda
3. Parameter GET: Sesuaikan dengan nama parameter yang ingin Anda gunakan untuk pencarian
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>