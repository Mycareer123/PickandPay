<?php
    require "config/db.php";


    // Fetch all active products from the database
    $sql = 'select * from products where status = 1 order by product_id desc';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PicknPay - Quality Appliances for Every Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="customer/assets/main.css">
</head>
<body>

<!-- ─── HEADER ─────────────────────────────────── -->
<header class="header">
    <div class="header-top">
        <a href="index.php" class="logo">
            <i class="ti ti-shopping-cart"></i>
            PicknPay
        </a>
        <div class="header-right">
            <a href="customer/views/cart.php">
                <button class="cart-btn" id="cartBtn" aria-label="View cart">
                    <i class="ti ti-shopping-cart"></i>
                    <span class="cart-badge" id="cartBadge">0</span>
                </button>
            </a>
            
        </div>
    </div>
    <div class="search-bar">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Search for appliances..." />
    </div>
</header>

<!-- ─── HERO ───────────────────────────────────── -->
<section class="hero">
    <div class="hero-circle"></div>
    <div class="hero-circle2"></div>
    <span class="hero-tag">🏠 Home Appliances</span>
    <h1 class="hero-title">Quality Appliances<br>for <span>Every Home</span></h1>
    <p class="hero-sub">Browse our wide range of home appliances delivered nationwide.</p>
    <!--Add shop now feature later -->
    <a href="products.php" target="_blank" class="hero-btn" style="display: none;">
        <i class="ti ti-shopping-bag"></i> Shop Now
    </a>
</section>

<!-- ─── FEATURED PRODUCTS ──────────────────────── -->
<section class="section">
    <div class="section-head">
        <span class="section-title">Featured Products</span>
        <a href="products.php" target="_blank" class="see-all">See all →</a>
    </div>
    <div class="product-grid" id="featuredGrid">

        <!-- PHP LOOP GOES HERE -->
        <!-- Example of one product tile — replace with PHP loop -->

        
        <?php foreach ($products as $product): ?>
        <div class="product-tile" data-id="<?= $product['product_id'] ?>">
            <?php
                $sql = "Select image_url from product_images where product_id = :product_id and display_order = :display_order";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':product_id' => $product['product_id'],
                    ':display_order' => 1
                ]);
                $mainImage = $stmt->fetch(PDO::FETCH_ASSOC);
                if(isset($mainImage['image_url'])) {
                    $imageUrl = str_replace("../../", "", $mainImage['image_url']); 
                } else {
                    $imageUrl = null; // or set a default image URL
                }
                
            ?>
            <div class="product-img">
                <?php if (!empty($mainImage['image_url'])): ?>
                    <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                <?php else: ?>
                    <i class="ti ti-package"></i>
                <?php endif; ?>
            </div>
            <div class="product-tile-info">
                <div class="product-tile-name" data-name="<?= htmlspecialchars($product['product_name'])?>"><?= htmlspecialchars($product['product_name']) ?></div>
                <div class="product-tile-price">GH₵<?= number_format($product['price_unit'], 2) ?></div>
                <button class="add-cart-btn" data-id="<?= $product['product_id'] ?>" data-name="<?= htmlspecialchars($product['product_name']) ?>" data-price="<?= $product['price_unit'] ?>">
                    <i class="ti ti-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── NEW ARRIVALS ────────────────────────────── -->
<section class="section" style="padding-top: 0;">
    <div class="section-head">
        <span class="section-title">New Arrivals</span>
        <a href="products.php" class="see-all">See all →</a>
    </div>
    <div class="product-grid" id="newArrivalsGrid">

        <!-- PHP LOOP GOES HERE -->
        <?php
            $sql = "select * from products where status = 1 order by product_id desc limit 4";
            $stmt = $conn->query($sql);
            $newArrivals = $stmt ->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <?php foreach ($newArrivals as $product): ?>
            <?php
                $sql = "Select image_url from product_images where product_id = :product_id and display_order = :display_order";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':product_id' => $product['product_id'],
                    ':display_order' => 1
                ]);
                $mainImage = $stmt->fetch(PDO::FETCH_ASSOC);
                if(isset($mainImage['image_url'])) {
                    $imageUrl = str_replace("../../","", $mainImage['image_url']); 
                } else {
                    $imageUrl = null; // or set a default image URL
                }
                
            ?>
        <div class="product-tile" data-id="<?= $product['product_id'] ?>">
            <div class="product-img">
                <?php if (!empty($imageUrl)): ?>
                    <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                <?php else: ?>
                    <i class="ti ti-package"></i>
                <?php endif; ?>
            </div>
            <div class="product-tile-info">
                <div class="product-tile-name" data-name ="<?= htmlspecialchars($product['product_name']) ?>"><?= htmlspecialchars($product['product_name']) ?></div>
                <div class="product-tile-price">GH₵<?= number_format($product['price_unit'], 2) ?></div>
                <button class="add-cart-btn" data-id="<?= $product['product_id'] ?>" data-name="<?= htmlspecialchars($product['product_name']) ?>" data-price="<?= $product['price_unit'] ?>">
                    <i class="ti ti-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ─── FOOTER ─────────────────────────────────── -->
<footer class="footer">
    <div class="footer-logo">
        <i class="ti ti-shopping-cart"></i> PicknPay
    </div>
    <p class="footer-tagline">Quality appliances for every home.<br>Delivered nationwide.</p>
    <div class="footer-links">
        <a href="#" class="footer-link">About Us</a>
        <a href="#" class="footer-link">Contact Us</a>
        <a href="track.php" class="footer-link">Track My Order</a>
        <a href="#" class="footer-link">Privacy Policy</a>
    </div>
    <hr class="footer-divider">
    <p class="footer-bottom">© 2026 PicknPay. All rights reserved.</p>
</footer>

<!-- ─── TOAST ─────────────────────────────────── -->
<div class="toast" id="toast"></div>

<!-- Your JS file goes here -->
<script src="customer/assets/main.js" defer></script>

</body>
</html>