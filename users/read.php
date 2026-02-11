<?php
include '../db.php';
header('Content-Type: application/json');

// Query ambil semua user
$sql = "SELECT id, nama, email, level, poin, created_at FROM users ORDER BY id DESC";
$result = $conn->query($sql);

$users = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "total" => count($users),
        "data" => $users
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}

$conn->close();
