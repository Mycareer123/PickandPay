//productData is in product_details.php
// ─── CART UTILITIES ───────────────────────────
function getCart() {
    return JSON.parse(localStorage.getItem('picknpay_cart')) || [];
}

function saveCart(cart) {
    localStorage.setItem('picknpay_cart', JSON.stringify(cart));
}

function updateCartBadge() {
    const cart = getCart();
    const total = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cartBadge').textContent = total;
}

// ─── CHECK CART STATUS ────────────────────────
function checkCartStatus() {
    const cart = getCart();
    const exists = cart.find(item => item.product_id === productData.product_id);
    const addBtn = document.getElementById('addToCartBtn');
    const goBtn  = document.getElementById('goToCartBtn');
    const outBtn = document.getElementById('outOfStockBtn');

    if (productData.quantity <= 0) {
        addBtn.style.display  = 'none';
        goBtn.style.display   = 'none';
        outBtn.style.display  = 'flex';
    } else if (exists) {
        addBtn.style.display  = 'none';
        goBtn.style.display   = 'flex';
        outBtn.style.display  = 'none';
    } else {
        addBtn.style.display  = 'flex';
        goBtn.style.display   = 'none';
        outBtn.style.display  = 'none';
    }
}

// ─── ADD TO CART ──────────────────────────────
document.getElementById('addToCartBtn').addEventListener('click', () => {
    const qty  = parseInt(document.getElementById('qtyValue').textContent);
    const cart = getCart();
    const exists = cart.find(item => item.product_id === productData.product_id);

    if (exists) {
        exists.quantity += qty;
    } else {
        cart.push({
            product_id: productData.product_id,
            name:       productData.product_name,
            price:      productData.price_unit,
            quantity:   qty
        });
    }

    saveCart(cart);
    updateCartBadge();
    checkCartStatus();
    showToast('Added to cart', 'success');
});

// ─── QUANTITY CONTROLS ────────────────────────
let qty = 1;
const maxQty = productData.quantity;

document.getElementById('plusBtn').addEventListener('click', () => {
    if (qty < maxQty) {
        qty++;
        document.getElementById('qtyValue').textContent = qty;
    }
});

document.getElementById('minusBtn').addEventListener('click', () => {
    if (qty > 1) {
        qty--;
        document.getElementById('qtyValue').textContent = qty;
    }
});

// ─── CAROUSEL ────────────────────────────────
let current = 0;
const slides = document.getElementById('slides');
const dots   = document.querySelectorAll('.dot');
const total  = dots.length;

function goTo(index) {
    current = (index + total) % total;
    slides.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
}

document.getElementById('prevBtn').addEventListener('click', () => goTo(current - 1));
document.getElementById('nextBtn').addEventListener('click', () => goTo(current + 1));
dots.forEach(dot => dot.addEventListener('click', () => goTo(Number(dot.dataset.index))));

// ─── BACK BUTTON ─────────────────────────────
document.getElementById('backBtn').addEventListener('click', () => {
    window.history.back();
});

// ─── TOAST ───────────────────────────────────
function showToast(message, type = '') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    setTimeout(() => toast.className = `toast ${type} show`, 1000);
    setTimeout(() => toast.classList.remove('show'), 6000);
}

// ─── INIT ────────────────────────────────────
updateCartBadge();
checkCartStatus();
