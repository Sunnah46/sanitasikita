<?php
include '../db.php';
header("Content-Type: application/json");

$id = $_POST['id'] ?? null;

$sql = "
SELECT 
    u.id,
    u.nama,
    u.email,
    u.level,
    u.poin,
    hk.skor,
    s.isi,
    s.kategori,
    s.tanggal
FROM users u

LEFT JOIN (
    SELECT user_id, skor
    FROM hasil_kuis
    WHERE id IN (
        SELECT MAX(id)
        FROM hasil_kuis
        GROUP BY user_id
    )
) hk ON hk.user_id = u.id

LEFT JOIN (
    SELECT user_id, isi, kategori, tanggal
    FROM saran
    WHERE id IN (
        SELECT MAX(id)
        FROM saran
        GROUP BY user_id
    )
) s ON s.user_id = u.id
";

if ($id) {
    $sql .= " WHERE u.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>
