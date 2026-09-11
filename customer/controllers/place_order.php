<?php
session_start();
header('Content-Type: application/json');
require "../../config/db.php";

// ─── READ JSON FROM JAVASCRIPT ───────────────────
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

// ─── EXTRACT CUSTOMER DETAILS ────────────────────
$fullName  = trim($data['full_name']);
$phone     = trim($data['phone']);
$email     = trim($data['email'] ?? '');
$address   = trim($data['address']);
$city      = trim($data['city']);
$region    = trim($data['region']);
$cart      = $data['cart'];

// ─── BASIC VALIDATION ────────────────────────────
if (!$fullName || !$phone || !$address || !$city || !$region || empty($cart)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

try {

    // ─── VERIFY PRODUCTS + CALCULATE TOTAL ───────
    $totalPrice = 0;
    $verifiedItems = [];

    foreach ($cart as $item) {
        $productId = (int) $item['product_id'];
        $quantity  = (int) $item['quantity'];

        // Fetch real price and name from database
        $sql  = "SELECT * FROM products WHERE product_id = :id AND status = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // If product not found or inactive skip it
        if (!$product) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'One or more products are no longer available'
            ]);
            exit();
        }

        if ($quantity > $product['quantity']) {
            echo json_encode([
                'status'  => 'error',
                'message' => "Sorry, only {$product['quantity']} units of {$product['product_name']} are available"
            ]);
            exit();
        }

        // Calculate subtotal for this item using real database price
        $subtotal    = $product['price_unit'] * $quantity;
        $totalPrice += $subtotal;

        $verifiedItems[] = [
            'product_id'   => $productId,
            'product_name' => $product['product_name'],
            'price'        => $product['price_unit'],
            'quantity'     => $quantity,
            'subtotal'     => $subtotal
        ];
    }

    // ─── GENERATE ORDER REFERENCE ─────────────────
    $year     = date('Y');
    $sql      = "SELECT COUNT(*) AS total FROM orders WHERE YEAR(order_date) = :year";
    $stmt     = $conn->prepare($sql);
    $stmt->execute([':year' => $year]);
    $row      = $stmt->fetch(PDO::FETCH_ASSOC);
    $orderNum = str_pad($row['total'] + 1, 5, '0', STR_PAD_LEFT);
    $orderRef = "ORD-{$year}-{$orderNum}";

    // ─── SAVE ORDER TO DATABASE ───────────────────
    $sql = "INSERT INTO orders 
                (order_ref, customer_name, customer_phone, customer_email, 
                 delivery_address, city, region, total_price, status)
            VALUES 
                (:order_ref, :customer_name, :customer_phone, :customer_email,
                 :delivery_address, :city, :region, :total_price, 'Pending')";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':order_ref'       => $orderRef,
        ':customer_name'   => $fullName,
        ':customer_phone'  => $phone,
        ':customer_email'  => $email,
        ':delivery_address'=> $address,
        ':city'            => $city,
        ':region'          => $region,
        ':total_price'     => $totalPrice
    ]);

    //reduce the stock of each product in the database
    foreach ($verifiedItems as $item) {
        $sql = "UPDATE products SET quantity = quantity - :quantity WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':quantity'   => $item['quantity'],
            ':product_id' => $item['product_id']
        ]);

        //set status to inactive if stock is 0
        $sql = "UPDATE products SET status = 0 WHERE product_id = :product_id AND quantity <= 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':product_id' => $item['product_id']
        ]);
    }

    // ─── SEND SUCCESS RESPONSE ────────────────────
    echo json_encode([
        'status'    => 'success',
        'order_ref' => $orderRef,
        'total'     => $totalPrice
    ]);
    exit();

} catch (PDOException $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Something went wrong: ' . $e->getMessage()
    ]);
    exit();
}