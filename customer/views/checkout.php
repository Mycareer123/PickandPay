<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PicknPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/checkout.css">
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
    <div class="header-spacer"></div>
</header>

<!-- ─── MAIN ───────────────────────────────────── -->
<main class="main">
    <p class="page-title">Checkout</p>
    <p class="page-sub">Fill in your delivery details below</p>

    <!-- DELIVERY DETAILS -->
    <div class="section-card">
        <p class="section-card-title">
            <i class="ti ti-map-pin"></i> Delivery Details
        </p>

        <label class="field-label" for="fullName">
            Full Name <span class="required">*</span>
        </label>
        <input class="field-input" type="text" id="fullName"
            name="full_name" placeholder="e.g. Kwame Mensah" />
        <p class="field-error" id="fullNameErr">Full name is required</p>

        <label class="field-label" for="phone">
            Phone Number <span class="required">*</span>
        </label>
        <input class="field-input" type="tel" id="phone"
            name="phone" placeholder="e.g. 0244000000" />
        <p class="field-error" id="phoneErr">Phone number is required</p>

        <label class="field-label" for="email">Email Address</label>
        <input class="field-input" type="email" id="email"
            name="email" placeholder="e.g. kwame@email.com" />
        <p class="field-error" id="emailErr">Enter a valid email address</p>
        <label class="field-label" for="address">
            Delivery Address <span class="required">*</span>
        </label>
        <input class="field-input" type="text" id="address"
            name="address" placeholder="e.g. 12 Accra Road" />
        <p class="field-error" id="addressErr">Delivery address is required</p>

        <div class="field-row">
            <div>
                <label class="field-label" for="city">
                    City <span class="required">*</span>
                </label>
                <input class="field-input" type="text" id="city"
                    name="city" placeholder="e.g. Accra" />
                <p class="field-error" id="cityErr">City is required</p>
            </div>
            <div>
                <label class="field-label" for="region">
                    Region <span class="required">*</span>
                </label>
                <select class="field-select" id="region" name="region">
                    <option value="">Select</option>
                    <option value="Greater Accra">Greater Accra</option>
                    <option value="Ashanti">Ashanti</option>
                    <option value="Western">Western</option>
                    <option value="Eastern">Eastern</option>
                    <option value="Central">Central</option>
                    <option value="Northern">Northern</option>
                    <option value="Volta">Volta</option>
                    <option value="Brong-Ahafo">Brong-Ahafo</option>
                    <option value="Upper East">Upper East</option>
                    <option value="Upper West">Upper West</option>
                    <option value="Oti">Oti</option>
                    <option value="Bono East">Bono East</option>
                    <option value="Ahafo">Ahafo</option>
                    <option value="Savannah">Savannah</option>
                    <option value="North East">North East</option>
                    <option value="Western North">Western North</option>
                </select>
                <p class="field-error" id="regionErr">Region is required</p>
            </div>
        </div>
    </div>

    <!-- PAYMENT METHOD -->
    <div class="section-card">
        <p class="section-card-title">
            <i class="ti ti-credit-card"></i> Payment Method
        </p>
        <div class="payment-tile">
            <div class="payment-icon">
                <i class="ti ti-cash"></i>
            </div>
            <div>
                <div class="payment-label">Payment on Delivery</div>
                <div class="payment-sub">Pay when your order arrives</div>
            </div>
            <i class="ti ti-circle-check payment-check"></i>
        </div>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="section-card">
        <p class="section-card-title">
            <i class="ti ti-receipt"></i> Order Summary
        </p>
        <div class="summary-row">
            <span class="summary-label" id="subtotalLabel">Subtotal</span>
            <span class="summary-value" id="subtotalValue">GH₵0.00</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Delivery Fee</span>
            <span class="delivery-pending">To be confirmed</span>
        </div>
        <hr class="summary-divider">
        <div class="summary-row">
            <span class="summary-total-label">Total</span>
            <span class="summary-total-value" id="totalValue">GH₵0.00</span>
        </div>
    </div>

    <!-- NOTE -->
    <div class="note-box">
        <i class="ti ti-info-circle"></i>
        <span class="note-text">
            Delivery fee will be confirmed by our team after your order is placed.
            You will be contacted via phone before delivery.
        </span>
    </div>

</main>

<!-- ─── PLACE ORDER BAR ──────────────────────────── -->
<div class="order-bar">
    <button class="order-btn" id="placeOrderBtn">
        <i class="ti ti-check"></i> Place Order
    </button>
</div>

<!-- ─── TOAST ─────────────────────────────────── -->
<div class="toast" id="toast"></div>

<!-- Your JS file goes here -->
<script src="../assets/checkout.js"></script>

</body>
</html>