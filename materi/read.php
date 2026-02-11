<?php
include '../db.php';
header('Content-Type: application/json');

$data = [];

if (isset($_GET['id']) || isset($_GET['kategori'])) {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM materi WHERE id = ?");
        $stmt->bind_param("i", $id);

    } else {
        $kategori = $_GET['kategori'];
        $stmt = $conn->prepare("SELECT * FROM materi WHERE kategori = ?");
        $stmt->bind_param("s", $kategori);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    $sql = "SELECT * FROM materi ORDER BY id ASC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode([
    "status" => "success",
    "total" => count($data),
    "data" => $data
]);

$conn->close();
