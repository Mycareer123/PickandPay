<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - PicknPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/cart.css">
</head>
<body>

<!-- ─── HEADER ─────────────────────────────────── -->
<header class="header">
    <button class="back-btn" id="backBtn" aria-label="Go back">
        <i class="ti ti-arrow-left"></i> Back
    </button>
    <a href="../../index.php" class="logo">
        <i class="ti ti-shopping-cart"></i> PicknPay
    </a>
    <div class="header-spacer"></div>
</header>

<!-- ─── MAIN ───────────────────────────────────── -->
<main class="main">
    <p class="page-title">My Cart</p>
    <p class="page-sub" id="cartCount">0 items in your cart</p>

    <!-- Cart items rendered here by JavaScript -->
    <div id="cartItems"></div>

    <!-- Empty state — shown when cart is empty -->
    <div class="empty-state" id="emptyState">
        <i class="ti ti-shopping-cart-off"></i>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added anything yet.</p>
        <a href="../../index.php" class="shop-btn">
            <i class="ti ti-shopping-bag"></i> Shop Now
        </a>
    </div>

    <!-- Order Summary — shown when cart has items -->
    <div class="summary-card" id="summaryCard" style="display:none;">
        <p class="summary-title">Order Summary</p>
        <div class="summary-row">
            <span class="summary-label" id="subtotalLabel">Subtotal (0 items)</span>
            <span class="summary-value" id="subtotalValue">GH₵0.00</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Delivery Fee</span>
            <span class="summary-value" id="deliveryFee">Calculated at checkout</span>
        </div>
        <hr class="summary-divider">
        <div class="summary-row">
            <span class="summary-total-label">Total</span>
            <span class="summary-total-value" id="totalValue">GH₵0.00</span>
        </div>
    </div>
</main>

<!-- ─── CHECKOUT BAR ────────────────────────────── -->
<div class="checkout-bar">
    <button class="checkout-btn" href="../assets/checkout.php" id="checkoutBtn" disabled>
        <i class="ti ti-lock"></i> Proceed to Checkout
    </button>
</div>

<!-- ─── TOAST ─────────────────────────────────── -->
<div class="toast" id="toast"></div>

<!-- Your JS file goes here -->
<script src="../assets/cart.js" defer></script>

</body>
</html>     