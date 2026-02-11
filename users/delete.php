<?php
include '../db.php';
header('Content-Type: application/json');

$id = $_POST['id'];

// 1️⃣ Hapus semua saran milik user
$stmt0 = $conn->prepare("DELETE FROM saran WHERE user_id = ?");
$stmt0->bind_param("i", $id);
$stmt0->execute();
$stmt0->close();

// 2️⃣ Hapus semua hasil kuis milik user
$stmt1 = $conn->prepare("DELETE FROM hasil_kuis WHERE user_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();
$stmt1->close();

// 3️⃣ Baru hapus user
$stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt2->bind_param("i", $id);

if ($stmt2->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "User, hasil kuis, dan saran berhasil dihapus"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt2->error
    ]);
}

$stmt2->close();
$conn->close();
?>
