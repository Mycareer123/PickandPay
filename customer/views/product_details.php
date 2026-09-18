<?php
    require "../../config/db.php";
    if($_SERVER['REQUEST_METHOD'] === "GET"){
        if($_GET['id']){
           $sql = "select * from products    where product_id=:id";
           $stmt = $conn->prepare($sql);
           $stmt -> execute([
            ":id" => $_GET['id']
           ]);

           $product = $stmt->fetch(PDO::FETCH_ASSOC);

           //FETCH PRODUCT IMAGES

           $sql = "select * from product_images where product_id = :id";
           $stmt = $conn->prepare($sql);
           $stmt -> execute([
            ':id' => $_GET['id']
           ]);

           $productImages = $stmt ->fetchAll(PDO::FETCH_ASSOC);
        }     
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - PicknPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/product_details.css">
   
</head>
<body>

<!-- ─── HEADER ─────────────────────────────────── -->
<header class="header">
    <button class="back-btn" id="backBtn" aria-label="Go back">
        <i class="ti ti-arrow-left"></i> Back
    </button>
    <a href="index.php" class="logo">
        <i class="ti ti-shopping-cart"></i> PicknPay
    </a>
    <button class="cart-btn" onclick="window.location.href='cart.php'" aria-label="View cart">
        <i class="ti ti-shopping-cart"></i>
        <span class="cart-badge" id="cartBadge">0</span>
    </button>
</header>

<!-- ─── CAROUSEL ───────────────────────────────── -->
<div class="carousel-wrap">
    <div class="carousel-main">
        <div class="carousel-slides" id="slides">
            <!-- PHP injects slides here -->
            <!-- Sample slides — replace with PHP loop -->
            <?php foreach ($productImages as $image): ?>
            <div class="carousel-slide">
                <img src="<?= htmlspecialchars($image['image_url']) ?>"
                     alt="<?= htmlspecialchars($product['product_name']) ?>">
            </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-prev" id="prevBtn" aria-label="Previous image">
            <i class="ti ti-chevron-left"></i>
        </button>
        <button class="carousel-next" id="nextBtn" aria-label="Next image">
            <i class="ti ti-chevron-right"></i>
        </button>
    </div>
    <div class="carousel-dots" id="dots">
        <?php foreach ($productImages as $index => $image): ?>
            <div class="dot <?= $index === 0 ? 'active' : '' ?>"
                 data-index="<?= $index ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ─── PRODUCT INFO ────────────────────────────── -->
<div class="product-info-card">
    <div class="product-title" id="productTitle">
        <?= htmlspecialchars($product['product_name']) ?>
    </div>
    <div class="product-price">GH₵<?= number_format($product['price_unit'], 2) ?></div>
    <?php if ($product['quantity'] > 0): ?>
        <span class="stock-badge">
            <i class="ti ti-circle-check"></i>
            In Stock (<?= $product['quantity'] ?> units)
        </span>
    <?php else: ?>
        <span class="out-of-stock-badge">
            <i class="ti ti-x"></i> Out of Stock
        </span>
    <?php endif; ?>
</div>

<!-- ─── DESCRIPTION ─────────────────────────────── -->
<div class="desc-card">
    <div class="desc-title">Description</div>
    <div class="desc-text" id="productDesc">
        <!--
        PHP version:
        <?= nl2br(htmlspecialchars($product['description'])) ?>
        -->
        A spacious double door refrigerator with a 300L capacity, perfect for families.
        Features frost-free cooling, adjustable shelves, and an energy-efficient compressor.
        Comes with a 2-year manufacturer warranty.
    </div>
</div>

<!-- ─── QUANTITY ─────────────────────────────────── -->
<div class="qty-card">
    <span class="qty-label">Quantity</span>
    <div class="qty-controls">
        <button class="qty-btn" id="minusBtn" aria-label="Decrease quantity">−</button>
        <span class="qty-value" id="qtyValue">1</span>
        <button class="qty-btn" id="plusBtn" aria-label="Increase quantity">+</button>
    </div>
</div>

<!-- ─── CART BAR ──────────────────────────────────── -->
<div class="cart-bar">
    <button class="add-cart-btn" id="addToCartBtn">
        <i class="ti ti-shopping-cart"></i> Add to Cart
    </button>
    <a href="cart.php" class="go-cart-btn" id="goToCartBtn">
        <i class="ti ti-shopping-cart"></i> Go to Cart
    </a>
    <button class="out-of-stock-btn" id="outOfStockBtn">
        <i class="ti ti-x"></i> Out of Stock
    </button>
</div>

<!-- ─── TOAST ─────────────────────────────────────── -->
<div class="toast" id="toast"></div>
<script>
    const productData = <?= json_encode($product)?>
</script>
<script src="../assets/product_details.js"></script>
</body>
</html>