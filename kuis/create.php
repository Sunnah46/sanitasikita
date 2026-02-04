<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id         = $_POST['id'];           // Nomor Induk Mahasiswa
$materi_id = $_POST['materi_id']; // Nama lengkap mahasiswa
$pertanyaan       = $_POST['pertanyaan'];         // Email mahasiswa
$pilihan_a  = $_POST['pilihan_a'];    // ID Jurusan mahasiswa
$pilihan_b = $_POST['pilihan_b']; // Tanggal lahir mahasiswa
$pilihan_c      = $_POST['pilihan_c'];        // Alamat mahasiswa
$jawaban_benar = $_POST['jawaban_benar']; // jawaban benar

try {
    // Mempersiapkan statement SQL untuk menyimpan data baru
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        INSERT INTO mahasiswa (id, materi_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, jawaban_benar)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$id, $materi_id, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $jawaban_benar]);

    // Jika eksekusi berhasil, ambil ID terakhir yang dimasukkan
    $last_id = $conn->lastInsertId();

    // Kirimkan respon sukses beserta data yang disimpan
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil ditambahkan",
        "data"    => [
            "id_mahasiswa"  => $last_id,
            "id"           => $id,
            "materi_id"  => $materi_id,
            "pertanyaan"         => $pertanyaan,
            "pilihan_a"    => $pilihan_a,
            "pilihan_b" => $pilihan_b,
            "pilihan_c"        => $pilihan_c,
            "jawaban_benar" => $jawaban_benar
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
2. Nama kolom: Ganti 'id', 'materi_id', 'pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>