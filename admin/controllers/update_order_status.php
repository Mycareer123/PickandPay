<?php
session_start();
header('Content-Type: application/json');
require "../../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $orderId   = (int) $_POST['order_id'];
        $newStatus = trim($_POST['status']);

        $allowed = ['Pending', 'Processing', 'Delivered'];
        if (!in_array($newStatus, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
            exit();
        }

        $sql  = "UPDATE orders SET status = :status WHERE order_id = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':status'   => $newStatus,
            ':order_id' => $orderId
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Status updated']);
        exit();

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit();
    }
}