<?php
// Koneksi ke database menggunakan file db.php
include_once "../db.php";

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id         = $_POST['id'];           // Nomor Induk Mahasiswa
$user_id = $_POST['user_id']; // Nama lengkap mahasiswa
$materi_id       = $_POST['materi_id'];         // Email mahasiswa
$skor  = $_POST['skor'];    // ID Jurusan mahasiswa
$tanggal = $_POST['tanggal']; // Tanggal lahir mahasiswa

try {
    // Mempersiapkan statement SQL untuk menyimpan data baru
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        INSERT INTO mahasiswa (id, user_id, materi_id, skor, tanggal)
        VALUES (?, ?, ?, ?, ?)
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$id, $user_id, $materi_id, $skor, $tanggal]);

    // Jika eksekusi berhasil, ambil ID terakhir yang dimasukkan
    $last_id = $conn->lastInsertId();

    // Kirimkan respon sukses beserta data yang disimpan
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil ditambahkan",
        "data"    => [
            "id_mahasiswa"  => $last_id,
            "id"           => $id,
            "user_id"  => $user_id,
            "materi_id"         => $materi_id,
            "skor"    => $skor,
            "tanggal" => $tanggal,
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
2. Nama kolom: Ganti 'id', 'user_id', 'materi_id', 'skor', 'tanggal', sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>