<?php
session_start();
header('Content-Type: application/json');
include "../../config/session.php";
require "../../config/db.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    try{
        $product_name = $_POST['product_name'];
        $product_status = $_POST['status'];
        $product_quantity = $_POST['stock_quantity'];
        $product_price = $_POST['price'];
        $product_id = (int) $_POST['product_id'];

        $removedIds = json_decode($_POST['removed_ids'] ?? '[]', true);
        if (!is_array($removedIds)) $removedIds = [];

        if($product_status == "Active" and $product_quantity > 0){
            $storage_status = 1;
        }else{
            $storage_status = 0;
        }

        $conn->beginTransaction();

        // 1. Update product fields
        $sql = "update products set product_name = :name, status = :status, quantity = :quantity, price_unit = :price where product_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $product_name,
            ':status' => $storage_status,
            ':quantity' => $product_quantity,
            ':price' => $product_price,
            ':id' => $product_id
        ]);

        // 2. Delete removed images (file + row)
        if (!empty($removedIds)) {
            $placeholders = implode(',', array_fill(0, count($removedIds), '?'));

            $stmt = $conn->prepare("SELECT image_url FROM product_images WHERE image_id IN ($placeholders) AND product_id = ?");
            $stmt->execute([...$removedIds, $product_id]);
            $filesToDelete = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($filesToDelete as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $stmt = $conn->prepare("DELETE FROM product_images WHERE image_id IN ($placeholders) AND product_id = ?");
            $stmt->execute([...$removedIds, $product_id]);
        }

        // 3. Insert newly uploaded images
        if (!empty($_FILES['images']['tmp_name'][0])) {
            $uploadDir = '../../uploads/';

            // Get the current highest display_order for this product
            $stmt = $conn->prepare("SELECT COALESCE(MAX(display_order), 0) AS max_order FROM product_images WHERE product_id = :id");
            $stmt->execute([':id' => $product_id]);
            $nextOrder = (int) $stmt->fetchColumn() + 1;

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpPath) {
                $originalName = basename($_FILES['images']['name'][$i]);
                $newFileName = uniqid() . '_' . $originalName;
                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($tmpPath, $destination)) {
                    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, display_order) VALUES (:product_id, :url, :order)");
                    $stmt->execute([
                        ':product_id' => $product_id,
                        ':url' => $destination,
                        ':order' => $nextOrder
                    ]);
                    $nextOrder++; // increment for the next new image in this same batch
                }
            }
        }

        $conn->commit();

        echo json_encode([
            'status' => "success",
            'message' => "Product Edited Successfully"
        ]);
    }catch(PDOException $e){
        $conn->rollBack();
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}