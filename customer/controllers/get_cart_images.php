<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$ids = implode(',', array_map('intval', $data['ids'])); // e.g. "1,7,12"

$sql = "SELECT product_id, image_url FROM product_images WHERE product_id IN ($ids)";
$stmt = $conn->query($sql);

$images = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $images[$row['product_id']] = $row['image_url'];
}

echo json_encode($images);
?>