<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$judul         = $_POST['judul'];          // Nomor Induk Mahasiswa
$deskripsi = $_POST['des$deskripsi']; // Nama lengkap mahasiswa
$kategori       = $_POST['kategori'];        // Email mahasiswa
$video_url  = $_POST['video_url'];   // ID Jurusan mahasiswa
$created_at = $_POST['created_at']; // Tanggal lahir mahasiswa    

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE mahasiswa
        SET judul = ?, deskripsi = ?, kategori = ?, video_url = ?, created_at = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$judul, $deskripsi, $kategori, $video_url, $created_at,]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil diperbarui",
        "data"    => [
            "id"  => $id,
            "judul"           => $judul,
            "des$deskripsi"  => $deskripsi,
            "kategori"         => $kategori,
            "video_url"    => $video_url,
            "created_at$" => $created_at,
            ]
    ]);

} catch(PDOException $e) {
    // Jika eksekusi gagal, kirimkan pesan error
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}

// Koneksi akan ditutup otomatis saat script selesai
/*
PETUNJUK UNTUK MENYESUAIKAN DENGAN SCHEMA TABEL LAIN:

Jika ingin menggunakan skema tabel yang berbeda, ubah bagian-bagian berikut:
1. Nama tabel: Ganti 'mahasiswa' dengan nama tabel Anda
2. Nama kolom: Ganti 'id', 'judul', 'deskripsi', 'kategori', 'video_url', 'created_at', sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>