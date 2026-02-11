<?php
include_once '../db.php';
header('Content-Type: application/json');

// Ambil id jika ada
$id = $_GET['id'] ?? null;

if ($id) {
    // READ BY ID
    $stmt = $conn->prepare("SELECT * FROM saran WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // READ ALL
    $result = $conn->query("SELECT * FROM saran ORDER BY id DESC");
}

// Cek data
if ($result->num_rows > 0) {

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "total_data" => count($data),
        "data" => $data
    ]);

} else {
    echo json_encode([
        "status" => "success",
        "message" => "Data tidak ditemukan",
        "data" => []
    ]);
}

$conn->close();
?>
