<?php
session_start();
header('Content-Type: application/json');   
require "../../config/session.php";
require "../../config/db.php";

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $product_id = $_GET['id'];
    $product_id = (int)$product_id; // Cast to integer for safety
    try{
        $sql = "SELECT image_id as id, image_url as url FROM    product_images WHERE product_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $product_id]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'images' => $images
        ]);
    }catch(PDOException $e){
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
    
}