<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$nama_lengkap         = $_POST['nama_lengkap'];          // Nomor Induk Mahasiswa
$email = $_POST['email']; // Nama lengkap mahasiswa
$password       = $_POST['password'];        // Email mahasiswa
$level  = $_POST['level'];   // ID Jurusan mahasiswa
$poin = $_POST['poin']; // Tanggal lahir mahasiswa
$created_at      = $_POST['created_at'];       // Alamat mahasiswa

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE mahasiswa
        SET nama_lengkap = ?, email = ?, password = ?, level = ?, poin = ?, created_at = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$nama_lengkap, $email, $password, $level, $poin, $created_at, $id]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil diperbarui",
        "data"    => [
            "id"  => $id,
            "nama_lengkap"           => $nama_lengkap,
            "email"  => $email,
            "password"         => $password,
            "level"    => $level,
            "poin" => $poin,
            "created_at"        => $created_at
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
2. Nama kolom: Ganti 'id', 'nama_lengkap', 'email', 'password', 'level', 'poin', 'created_at' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>