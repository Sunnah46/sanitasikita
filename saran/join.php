<?php
include '../db.php';
header('Content-Type: application/json');

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => "error",
        "message" => "user_id wajib dikirim"
    ]);
    exit;
}

$stmt = $conn->prepare("
    SELECT 
        s.*, 
        u.nama, 
        u.email
    FROM saran s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "total_data" => count($data),
    "data" => $data
]);

$stmt->close();
$conn->close();
?>
