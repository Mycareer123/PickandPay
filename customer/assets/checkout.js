document.getElementById('backBtn').addEventListener('click', () => {
    window.history.back();
});
// ─── UTILITY ─────────────────────────────────────
function getCart() {
    return JSON.parse(localStorage.getItem('picknpay_cart')) || [];
}

function showToast(message, type = '') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ─── POPULATE ORDER SUMMARY FROM LOCALSTORAGE ────
function populateSummary() {
    const cart = getCart();

    if (cart.length === 0) {
        window.location.href = 'cart.php';
        return;
    }

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

    document.getElementById('subtotalLabel').textContent = `Subtotal (${totalItems} item${totalItems > 1 ? 's' : ''})`;
    document.getElementById('subtotalValue').textContent = `GH₵${subtotal.toLocaleString()}`;
    document.getElementById('totalValue').textContent = `GH₵${subtotal.toLocaleString()}`;
}

// ─── VALIDATE FORM ───────────────────────────────
function validateForm() {
    let isValid = true;

    const fields = [
        { id: 'fullName', errId: 'fullNameErr', message: 'Full name is required' },
        { id: 'phone', errId: 'phoneErr', message: 'Phone number is required' },
        { id: 'address', errId: 'addressErr', message: 'Delivery address is required' },
        { id: 'city', errId: 'cityErr', message: 'City is required' },
        { id: 'region', errId: 'regionErr', message: 'Please select a region' },
    ];

    fields.forEach(field => {
        const input = document.getElementById(field.id);
        const err = document.getElementById(field.errId);

        if (!input.value.trim()) {
            input.classList.add('error');
            err.textContent = field.message;
            err.classList.add('show');
            isValid = false;
        } else {
            input.classList.remove('error');
            err.classList.remove('show');
        }
    });
    return isValid;
}

// ─── CLEAR ERRORS ON INPUT ───────────────────────
['fullName', 'phone', 'address', 'city', 'region'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        document.getElementById(id).classList.remove('error');
    });
});

// ─── PLACE ORDER ─────────────────────────────────
document.getElementById('placeOrderBtn').addEventListener('click', async () => {
    if (!validateForm()) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    const cart = getCart();

    // Build cart payload — only IDs and quantities
    const cartPayload = cart.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity
    }));

    // Build full payload
    const payload = {
        full_name:    document.getElementById('fullName').value.trim(),
        phone:        document.getElementById('phone').value.trim(),
        email:        document.getElementById('email').value.trim(),
        address:      document.getElementById('address').value.trim(),
        city:         document.getElementById('city').value.trim(),
        region:       document.getElementById('region').value,
        cart:         cartPayload
    };

    // Loading state
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader"></i> Placing Order...';

    try {
        const response = await fetch('../controllers/place_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.status === 'success') {
            // Clear cart from localStorage
            localStorage.removeItem('picknpay_cart');

            // Redirect to confirmation page with order reference
            window.location.href = `confirmation.php?ref=${result.order_ref}`;
        } else {
            showToast(result.message || 'Something went wrong', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-check"></i> Place Order';
        }

    } catch (error) {
        showToast('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-check"></i> Place Order';
    }
});

const placeOrderBtn = document.getElementById('placeOrderBtn');
// ─── LIVE PHONE VALIDATION ───────────────────────
document.getElementById('phone').addEventListener('input', () => {
    const phone = document.getElementById('phone').value.trim();
    const phonePattern = /^0[0-9]{9}$/;
    const phoneErr = document.getElementById('phoneErr');

    if (phone && !phonePattern.test(phone)) {
        document.getElementById('phone').classList.add('error');
        phoneErr.textContent = 'Enter a valid phone number e.g. 0244000000';
        phoneErr.classList.add('show');
        placeOrderBtn.disabled = true;
    } else {
        document.getElementById('phone').classList.remove('error');
        phoneErr.classList.remove('show');
        placeOrderBtn.disabled = false;
    }
});

// ─── LIVE EMAIL VALIDATION ───────────────────────
document.getElementById('email').addEventListener('input', () => {
    const email = document.getElementById('email').value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailErr = document.getElementById('emailErr');

    if (email && !emailPattern.test(email)) {
        document.getElementById('email').classList.add('error');
        emailErr.textContent = 'Enter a valid email address';
        emailErr.classList.add('show');
        placeOrderBtn.disabled = true;
    } else {
        document.getElementById('email').classList.remove('error');
        emailErr.classList.remove('show');
        placeOrderBtn.disabled = false;
    }
});

// ─── INIT ────────────────────────────────────────
populateSummary();
