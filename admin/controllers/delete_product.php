<?php
    session_start();
    header('Content-Type: application/json');
    require "../../config/db.php";
    require "../../config/session.php";

    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        try{
            $id = $_POST['id'];

            $conn->beginTransaction();

            // 1. Grab image paths before deleting rows
            $stmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = :id");
            $stmt->execute([':id' => $id]);
            $imagePaths = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // 2. Delete the images rows first (FK requires this before deleting the product)
            $stmt = $conn->prepare("DELETE FROM product_images WHERE product_id = :id");
            $stmt->execute([':id' => $id]);

            // 3. Now the product row can be deleted safely
            $sql = 'delete from products where product_id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $id]);

            $conn->commit();

            // 4. Only delete files from disk after DB commit succeeds
            foreach ($imagePaths as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            echo json_encode([
                'status' => 'success',
                'message' => "Product deleted successfully"
            ]);
            exit();

        }catch(PDOException $e){
            $conn->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }