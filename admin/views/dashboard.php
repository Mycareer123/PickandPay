<?php
    session_start();
    session_regenerate_id();
    require "../../config/db.php";
    include "../../config/session.php";

    //fetching the total number of products from the database
    $selectProductsNumber = 'select count(*) as total_number from products;';

    $stmt = $conn->query($selectProductsNumber);

    $productCount = $stmt -> fetch(PDO::FETCH_ASSOC);

    //get admin Name
    $admin_id = $_SESSION['admin_id'];
    $getName = 'select first_name, last_name from admin where admin_id = :admin_id';
    $stmt = $conn -> prepare($getName);
    $stmt -> execute([
        'admin_id' => $admin_id
    ]);
    $admin = $stmt -> fetch(PDO::FETCH_ASSOC);
    $admin_name = $admin['first_name']." ". $admin['last_name'];

    //fetching the total number of orders from the database
    $selectOrderNumberInMOnth = 'SELECT * FROM orders WHERE MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE())';
    $stmt = $conn->query($selectOrderNumberInMOnth);
    $orderCount = $stmt -> rowCount();
?>

<?php
    // ─── FETCH RECENT ORDERS ─────────────────────────
    // Add this to the top of your dashboard.php with your other DB queries
    $sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 25";
    $stmt = $conn->query($sql);
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playwrite+GB+J:ital,wght@0,100..400;1,100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <title>Dashboard</title>
</head>
<body>
    <?php
        include "sidebar.php";
    ?>
    
   <main>
        <h3 class="title">Dashboard</h3>
        <p class="subTitle">Welcome back, <?php echo $admin_name ?></p>

        <div class="statContainer">
            <div class="stat">
                <i class="ti ti-package productIcon"></i>
                <p class="statLabel">Total Products</p>
                <h2 class="statNumber"><?= $productCount['total_number'] ?></h2>
            </div>
            <div class ="stat">
                <i class="ti ti-shopping-bag orderIcon"></i>
                <p class="statLabel">Total Orders</p>
                <h2 class="statNumber"><?= $orderCount ?></h2>
                
            </div>
        </div>

        <p class="section-title">Recent Orders</p>
 
        <div class="orders-card">
            <div class="orders-card-head">
                <span class="orders-card-title">Latest Orders</span>
            </div>
        
            <?php if (empty($recentOrders)): ?>
            <div style="text-align:center; padding: 32px 16px; color: #888; font-size: 13px;">
                No orders yet.
            </div>
        
            <?php else: ?>
            <?php foreach ($recentOrders as $order): ?>
            <div class="order-row">
                <div class="order-row-top">
                    <span class="order-ref"><?= htmlspecialchars($order['order_ref']) ?></span>
                    <span class="order-date"><?= date('d M Y', strtotime($order['order_date'])) ?></span>
                </div>
                <div class="order-row-mid">
                    <span class="order-customer">
                        <?= htmlspecialchars($order['customer_name']) ?> · <?= htmlspecialchars($order['customer_phone']) ?>
                    </span>
                    <span class="order-total">GH₵<?= number_format($order['total_price'], 2) ?></span>
                </div>
                <div class="order-row-bottom">
                    <select class="status-select <?= strtolower($order['status']) ?>"
                            data-id="<?= $order['order_id'] ?>">
                        <option value="Pending"    <?= $order['status'] === 'Pending'    ? 'selected' : '' ?>>Pending</option>
                        <option value="Processing" <?= $order['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="Delivered"  <?= $order['status'] === 'Delivered'  ? 'selected' : '' ?>>Delivered</option>
                    </select>
                    <button class="save-status-btn" data-id="<?= $order['order_id'] ?>">Save</button>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div id="toast" class="toast"></div>
   </main>
   <script src="../assets/dashboard.js"></script>
</body>
</html>