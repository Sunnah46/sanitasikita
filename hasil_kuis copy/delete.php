<?php
include_once "../db.php";

header('Content-Type: application/json');

// Ambil ID dari POST
$user_id = $_POST['id'];

try {
    // Siapkan SQL
    $stmt = $conn->prepare("DELETE FROM hasil_kuis WHERE user_id = ?");

    // Eksekusi dengan parameter
    $stmt->execute([$user_id]);

    // Cek apakah ada data yang terhapus
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status"  => "success",
            "message" => "Data berhasil dihapus"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Data tidak ditemukan"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
