<?php
session_start();
require "../../config/session.php";
require "../../config/db.php";


if($_SERVER['REQUEST_METHOD'] === "GET"){
    try{
        $sql = "Select * from products where product_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $_GET['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);


        
        
        echo json_encode([
            'status' => 'success',
            'product' => $product
        ]);
    }catch(PDOException $e){
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}