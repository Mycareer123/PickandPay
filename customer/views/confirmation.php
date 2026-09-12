<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - PicknPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/confirmation.css">
</head>
<body>

<!-- ─── HEADER ─────────────────────────────────── -->
<header class="header">
    <a href="../../index.php" class="logo">
        <i class="ti ti-shopping-cart"></i> PicknPay
    </a>
</header>

<!-- ─── MAIN ───────────────────────────────────── -->
<main class="main">

<?php
session_start();
require "../../config/db.php";

// Read order reference from URL
$orderRef = isset($_GET['ref']) ? trim($_GET['ref']) : '';

if (empty($orderRef)) {
    // No reference — show error
    echo "
    <div class='error-state'>
        <i class='ti ti-mood-sad'></i>
        <h3>No Order Found</h3>
        <p>We could not find your order. Please check your reference number.</p>
        <a href='../index.php' class='track-btn' style='margin: 0 auto; width: auto; padding: 0 24px;'>
            <i class='ti ti-home'></i> Go Home
        </a>
    </div>
    ";
} else {
    // Fetch order from database
    try {
        $sql  = "SELECT * FROM orders WHERE order_ref = :order_ref";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':order_ref' => $orderRef]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo "
            <div class='error-state'>
                <i class='ti ti-mood-sad'></i>
                <h3>Order Not Found</h3>
                <p>We could not find an order with reference <strong>{$orderRef}</strong>.</p>
                <a href='index.php' class='track-btn' style='margin: 0 auto; width: auto; padding: 0 24px;'>
                    <i class='ti ti-home'></i> Go Home
                </a>
            </div>
            ";
        } else {
            // Format date
            $date = date('d M Y, h:i A', strtotime($order['order_date']));
        ?>

        <!-- SUCCESS BANNER -->
        <div class="success-banner">
            <div class="success-icon">
                <i class="ti ti-check"></i>
            </div>
            <h1 class="success-title">Order Placed!</h1>
            <p class="success-sub">Thank you for your order. We will contact you shortly to confirm your delivery.</p>
        </div>

        <!-- ORDER REFERENCE -->
        <div class="section-card">
            <p class="section-card-title">
                <i class="ti ti-receipt"></i> Your Order Reference
            </p>
            <div class="order-ref-box">
                <p class="order-ref-label">Reference Number</p>
                <p class="order-ref-value"><?= htmlspecialchars($order['order_ref']) ?></p>
                <p class="order-ref-hint">Save this number to track your order</p>
            </div>
        </div>

        <!-- ORDER DETAILS -->
        <div class="section-card">
            <p class="section-card-title">
                <i class="ti ti-info-circle"></i> Order Details
            </p>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value"><?= $date ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value status-pending"><?= htmlspecialchars($order['status']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment</span>
                <span class="detail-value">Payment on Delivery</span>
            </div>
            <hr class="detail-divider">
            <div class="detail-row">
                <span class="detail-total-label">Total</span>
                <span class="detail-total-value">GH₵<?= number_format($order['total_price'], 2) ?></span>
            </div>
        </div>

        <!-- DELIVERY DETAILS -->
        <div class="section-card">
            <p class="section-card-title">
                <i class="ti ti-map-pin"></i> Delivery Details
            </p>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value"><?= htmlspecialchars($order['customer_name']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone</span>
                <span class="detail-value"><?= htmlspecialchars($order['customer_phone']) ?></span>
            </div>
            <?php if (!empty($order['customer_email'])): ?>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?= htmlspecialchars($order['customer_email']) ?></span>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <span class="detail-label">Address</span>
                <span class="detail-value"><?= htmlspecialchars($order['delivery_address']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">City</span>
                <span class="detail-value"><?= htmlspecialchars($order['city']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Region</span>
                <span class="detail-value"><?= htmlspecialchars($order['region']) ?></span>
            </div>
        </div>

        <!-- NOTE -->
        <div class="note-box">
            <i class="ti ti-phone"></i>
            <span class="note-text">
                Our team will call you on <strong><?= htmlspecialchars($order['customer_phone']) ?></strong>
                to confirm your delivery details and fee before dispatch.
            </span>
        </div>

        <!-- BUTTONS -->
        <div class="btn-group">
            <a href="track.php?ref=<?= urlencode($order['order_ref']) ?>" class="track-btn">
                <i class="ti ti-map-search"></i> Track My Order
            </a>
            <a href="../../index.php" class="shop-btn">
                <i class="ti ti-shopping-bag"></i> Continue Shopping
            </a>
        </div>

        <?php
        }
    }catch(PDOException $e){
        echo "
        <div class='error-state'>
            <i class='ti ti-mood-sad'></i>
            <h3>Something Went Wrong</h3>
            <p>We could not load your order details. Please contact us with your reference number.</p>
        </div>
        ";
    }
}
?>

</main>

</body>
</html>