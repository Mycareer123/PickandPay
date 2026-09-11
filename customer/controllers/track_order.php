<?php
header('Content-Type: application/json');
require "../../config/db.php";

$ref  = isset($_GET['ref']) ? trim($_GET['ref']) : '';

if (!$ref) {
    echo json_encode(['status' => 'error', 'message' => 'No reference provided']);
    exit();
}

try {
    $sql  = "SELECT * FROM orders WHERE order_ref = :ref";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':ref' => $ref]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        echo json_encode(['status' => 'success', 'order' => $order]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    }
    exit();

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit();
}