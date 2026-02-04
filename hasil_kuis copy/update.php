<?php
// Koneksi ke database menggunakan file db.php
include_once "../db.php";

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$user_id         = $_POST['user_id'];          // Nomor Induk Mahasiswa
$materi_id = $_POST['materi_id']; // Nama lengkap mahasiswa
$skor       = $_POST['skor'];        // Email mahasiswa
$tanggal  = $_POST['tanggal'];   // ID Jurusan mahasiswa

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE mahasiswa
        SET user_id = ?, materi_id = ?, skor = ?, tanggal = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$user_id, $materi_id, $skor, $tanggal,]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil diperbarui",
        "data"    => [
            "id"  => $id,
            "user_id"           => $user_id,
            "materi_id"  => $nama_lengkap,
            "skor"         => $skor,
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
2. Nama kolom: Ganti 'id', 'user_id', 'nama_lengkap', 'skor', 'id_jurusan', 'tanggal_lahir', 'alamat' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>