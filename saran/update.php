<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$user_id         = $_POST['user_id'];          // Nomor Induk Mahasiswa
$kategori = $_POST['kategori']; // Nama lengkap mahasiswa
$isi       = $_POST['isi'];        // Email mahasiswa
$tanggal  = $_POST['tanggal'];   // tanggal

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE mahasiswa
        SET user_id = ?, kategori = ?, isi = ?, tanggal = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$user_id, $kategori, $isi, $tanggal]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil diperbarui",
        "data"    => [
            "id"  => $id,
            "user_id"           => $user_id,
            "kategori"  => $kategori,
            "isi"         => $isi,
            "tanggal"    => $tanggal,
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
2. Nama kolom: Ganti 'id', 'user_id', 'kategori', 'isi', 'tanggal', sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>