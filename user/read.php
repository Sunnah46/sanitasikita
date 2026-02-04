<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Array untuk menyimpan data hasil query
$data = [];

try {
    // Cek apakah ada parameter GET 'nama' atau 'id'
    // Jika ada, maka hanya ambil data spesifik berdasarkan parameter tersebut
    if (isset($_GET['nama']) || isset($_GET['id'])) {

        // Jika parameter 'nama' disediakan, cari berdasarkan nama
        if (isset($_GET['nama'])) {
            $nama = $_GET['nama'];
            // Mempersiapkan statement SQL untuk mencari data mahasiswa beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.nama, j.email, j.password, j.level, j.poin, j.created_at
                FROM user m
                LEFT JOIN jurusan j ON m.nama = j.nama
                WHERE m.nama = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$nama]);
        } else {
            // Jika parameter 'id' disediakan, cari berdasarkan id
            $id = $_GET['id'];
            // Mempersiapkan statement SQL untuk mencari data user beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.nama, j.email, j.password, j.level, j.poin, j.created_at
                FROM user m
                LEFT JOIN jurusan j ON m.nama = j.nama
                WHERE m.id_mahasiswa = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$id]);
        }

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // Jika tidak ada parameter GET, ambil semua data user beserta jurusan
        $stmt = $conn->prepare("
            SELECT m.*, j.nama, j.email, j.password, j.level, j.poin, j.created_at
            FROM user m
            LEFT JOIN jurusan j ON m.nama = j.nama
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
1. Nama tabel: Ganti 'user' dan 'jurusan' dengan nama tabel Anda
2. Nama kolom: Ganti kolom sesuai dengan kolom pencarian di tabel Anda
3. Parameter GET: Sesuaikan dengan nama parameter yang ingin Anda gunakan untuk pencarian
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>