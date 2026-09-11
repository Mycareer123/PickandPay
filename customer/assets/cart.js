//Return to previous page on back button click
const backButton = document.getElementById('backBtn');
backButton.addEventListener("click", () => {
   window.history.back();  
});

//get Cart from localStorage and Cart Functionalities like update and save
// ─── READ CART FROM LOCALSTORAGE ─────────────────
function getCart() {
    return JSON.parse(localStorage.getItem('picknpay_cart')) || [];
}

function saveCart(cart) {
    localStorage.setItem('picknpay_cart', JSON.stringify(cart));
}

// ─── UPDATE CART BADGE ───────────────────────────
function updateCartBadge() {
    const cart = getCart();
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const badge = document.getElementById('cartBadge');
    if (badge) badge.textContent = totalItems;
}

// ─── RENDER CART ITEMS ───────────────────────────
async function renderCart() {
    const cart = getCart();
    const cartItems = document.getElementById('cartItems');
    const emptyState = document.getElementById('emptyState');
    const summaryCard = document.getElementById('summaryCard');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const cartCount = document.getElementById('cartCount');

    cartItems.innerHTML = '';

    if (cart.length === 0) {
        emptyState.style.display = 'block';
        summaryCard.style.display = 'none';
        checkoutBtn.disabled = true;
        cartCount.textContent = '0 items in your cart';
        return;
    }

    emptyState.style.display = 'none';
    summaryCard.style.display = 'block';
    checkoutBtn.disabled = false;

    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = `${totalItems} item${totalItems > 1 ? 's' : ''} in your cart`;

    // Fetch images for all items in the cart, keyed by id
    const ids = cart.map(item => item.product_id);
    let images = {};
    try {
        const response = await fetch('../controllers/get_cart_images.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids })
        });
        images = await response.json();
    } catch (err) {
        console.error('Failed to fetch cart images:', err);
    }

    // Render each item
    cart.forEach((item, index) => {
        const imgPath = images[item.product_id];
        const thumbContent = imgPath
            ? `<img src="../uploads/${imgPath}" alt="${item.name}">`
            : `<i class="ti ti-package"></i>`;

        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <div class="item-thumb">
                ${thumbContent}
            </div>
            <div class="item-info">
                <div class="item-name">${item.name}</div>
                <div class="item-price">GH₵${Number(item.price).toLocaleString()}</div>
                <div class="item-controls">
                    <button class="qty-btn minus-btn" data-index="${index}">−</button>
                    <span class="qty-value">${item.quantity}</span>
                    <button class="qty-btn plus-btn" data-index="${index}">+</button>
                    <button class="remove-btn" data-index="${index}">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        `;
        cartItems.appendChild(div);
    });

    updateSummary(cart);
    attachCartListeners();
}

// ─── UPDATE ORDER SUMMARY ────────────────────────
function updateSummary(cart) {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

    document.getElementById('subtotalLabel').textContent = `Subtotal (${totalItems} item${totalItems > 1 ? 's' : ''})`;
    document.getElementById('subtotalValue').textContent = `GH₵${subtotal.toLocaleString()}`;
    document.getElementById('totalValue').textContent = `GH₵${subtotal.toLocaleString()}`;
}

// ─── CART ITEM CONTROLS ──────────────────────────
function attachCartListeners() {

    // Increase quantity
    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const cart = getCart();
            const index = parseInt(btn.dataset.index);
            cart[index].quantity += 1;
            saveCart(cart);
            renderCart();
            updateCartBadge();
        });
    });

    // Decrease quantity
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const cart = getCart();
            const index = parseInt(btn.dataset.index);
            if (cart[index].quantity > 1) {
                cart[index].quantity -= 1;
            } else {
                // Remove item if quantity reaches 0
                cart.splice(index, 1);
            }
            saveCart(cart);
            renderCart();
            updateCartBadge();
        });
    });

    // Remove item
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const cart = getCart();
            const index = parseInt(btn.dataset.index);
            cart.splice(index, 1);
            saveCart(cart);
            renderCart();
            updateCartBadge();
            showToast('Item removed from cart');
        });
    });
}

// ─── CHECKOUT BUTTON ─────────────────────────────
document.getElementById('checkoutBtn').addEventListener('click', () => {
    window.location.href = 'checkout.php';
});

// ─── TOAST ───────────────────────────────────────
function showToast(message, type = '') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ─── INIT ────────────────────────────────────────
renderCart();
updateCartBadge();