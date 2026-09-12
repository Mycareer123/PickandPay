<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - PicknPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/track.css">
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
    <p class="page-title">Track Order</p>
    <p class="page-sub">Enter your order reference to see the status</p>

    <!-- SEARCH -->
    <div class="search-card">
        <p class="search-card-title">Enter your order reference number</p>
        <div class="search-row">
            <input class="ref-input" type="text" id="refInput"
                placeholder="e.g. ORD-2026-00001" />
            <button class="track-btn" id="trackBtn">Track</button>
        </div>
        <p class="search-error" id="searchError">Please enter your order reference number</p>
    </div>

    <!-- LOADING -->
    <div class="loading" id="loading">
        <i class="ti ti-loader"></i> Looking up your order...
    </div>

    <!-- NOT FOUND -->
    <div class="not-found" id="notFound">
        <i class="ti ti-mood-sad"></i>
        <h3>Order Not Found</h3>
        <p>We could not find an order with that reference number. Please check and try again.</p>
    </div>

    <!-- RESULT AREA — shown after successful search -->
    <div class="result-area" id="resultArea">

        <!-- STATUS CARD -->
        <div class="section-card">
            <p class="section-card-title">
                <i class="ti ti-map-search"></i> Order Status
            </p>
            <div class="ref-badge">
                <span class="ref-badge-label">Reference</span>
                <span class="ref-badge-value" id="resultRef"></span>
            </div>

            <!-- TIMELINE — built by JavaScript -->
            <div class="timeline" id="timeline"></div>
        </div>

        <!-- ORDER DETAILS -->
        <div class="section-card">
            <p class="section-card-title">
                <i class="ti ti-info-circle"></i> Order Details
            </p>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value" id="resultDate"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment</span>
                <span class="detail-value">Payment on Delivery</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value" id="resultName"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone</span>
                <span class="detail-value" id="resultPhone"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Address</span>
                <span class="detail-value" id="resultAddress"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Region</span>
                <span class="detail-value" id="resultRegion"></span>
            </div>
            <hr class="detail-divider">
            <div class="detail-row">
                <span class="detail-total-label">Total</span>
                <span class="detail-total-value" id="resultTotal"></span>
            </div>
        </div>

    </div>

    <!-- CONTINUE SHOPPING -->
    <a href="../../index.php" class="shop-btn">
        <i class="ti ti-shopping-bag"></i> Continue Shopping
    </a>

</main>
<script src="../assets/track.js"></script>
</body>
</html>