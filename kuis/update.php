<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$materi_id         = $_POST['materi_id'];          // Nomor Induk Mahasiswa
$pertanyaan = $_POST['pertanyaan']; // Nama lengkap mahasiswa
$pilihan_a       = $_POST['pilihan_a'];        // Email mahasiswa
$pilihan_b  = $_POST['pilihan_b'];   // ID Jurusan mahasiswa
$pilihan_c = $_POST['pilihan_c']; // Tanggal lahir mahasiswa
$jawaban_benar      = $_POST['jawaban_benar'];       // Alamat mahasiswa

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE mahasiswa
        SET materi_id = ?, pertanyaan = ?, pilihan_a = ?, pilihan_b = ?, pilihan_c = ?, jawaban_benar = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$materi_id, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $jawaban_benar, $id]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil diperbarui",
        "data"    => [
            "id"  => $id,
            "materi_id"           => $materi_id,
            "pertanyaan"  => $pertanyaan,
            "pilihan_a"         => $pilihan_a,
            "pilihan_b"    => $pilihan_b,
            "pilihan_c" => $pilihan_c,
            "jawaban_benar"        => $jawaban_benar
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
1. Nama tabel: Ganti 'id' dengan nama tabel Anda
2. Nama kolom: Ganti 'id', 'materi_id', 'pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'jawaban_benar' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>