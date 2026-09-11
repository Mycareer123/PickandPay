<?php
session_start();
header('Content-Type: application/json');
require "../../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}  
// ─── ADD PRODUCT ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $productName     = $_POST['product_name'];
        $productPrice    = $_POST['price'];
        $productQuantity = $_POST['stock_quantity'];
        $productImage = $_FILES['images']['name'];
        if($_POST['status'] == 'Active'){
            $productStatus = 1;
        }else{
            $productStatus = 0;
        }

        $sql = 'INSERT INTO products(product_name, status, price_unit, quantity) 
                VALUES(:product_name, :status, :price_unit, :quantity)';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':product_name' => $productName,
            ':status'       => $productStatus,
            ':price_unit'   => $productPrice,
            ':quantity'     => $productQuantity,
        ]);

        //Uploading images to the root folder upload then the directory to the database.
        $images = $_FILES['images'];
        $imageName = $images['name'];
        $imageError = $images['error'];
        $imageType = $images['type'];
        $i = 0;
        $lastInsertId = $conn->lastInsertId();
        foreach ($imageName as $name){
            if (empty($name)) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Please select an image to upload'
                ]);
                exit();
            }

            $imageTmpName = $images['tmp_name'][$i];
            $imageExt = explode('.', $imageName[$i]);
            $actualExt = strtolower(end($imageExt));

            //validation of file extension
            $allowed = array('jpg', 'jpeg', 'png');
            if(in_array($actualExt, $allowed)){
                if($imageError[$i] === 0){
                $imageNewName = uniqid('', true).".".$actualExt;

                $imageDestination = "../../uploads/".$imageNewName;
                    if (move_uploaded_file($imageTmpName, $imageDestination)) {
                        $sql = "Insert into product_images(product_id, image_url, display_order) values(:product_id, :image_path, :display_order)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([
                            ':product_id' => $lastInsertId,
                            ':image_path' => $imageDestination,
                            ':display_order' =>$i + 1
                        ]);
                    } else {
                        echo "Upload FAILED for: " . $imageDestination;
                        var_dump(error_get_last());
                    }
                }else{
                    echo "There was an error uploading your file";
                };
            }else{
                exit("You cannot upload files of this type");
            }
            $i++;
            
        }
        echo json_encode([
            'status'  => 'success',
            'message' => 'Product added successfully'
        ]);
        exit();

    } catch (PDOException $e) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
        exit();
    }
}

// // ─── GET SINGLE PRODUCT ──────────────────────
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     try {
//         $id = $_GET['id'];

//         $sql  = "SELECT * FROM products WHERE product_id = :id";
//         $stmt = $conn->prepare($sql);
//         $stmt->execute([':id' => $id]);

//         $product = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($product) {
//             echo json_encode([
//                 'status'  => 'success',  // fixed: was "successful"
//                 'product' => $product
//             ]);
//         } else {
//             echo json_encode([
//                 'status'  => 'error',
//                 'message' => 'Product not found'
//             ]);
//         }
//         exit();

//     } catch (PDOException $e) {
//         echo json_encode([
//             'status'  => 'error',
//             'message' => 'Error: ' . $e->getMessage()
//         ]);
//         exit();
//     }
// }