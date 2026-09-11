const searchInput = document.querySelector("#searchInput");
const itemName = document.querySelectorAll(".product-tile-name");
const card = document.querySelectorAll('.product-tile');

searchInput.addEventListener("input", () => {
    card.forEach((cardItem, i) => {
        const name = (itemName[i]?.dataset.name || "").toLowerCase();
        if (name.includes(searchInput.value.toLowerCase())) {
            cardItem.style.display = 'block';
        } else {
            cardItem.style.display = "none";
        }
    });
});

// Read existing cart or start with empty array
function getCart() {
    return JSON.parse(localStorage.getItem('picknpay_cart')) || [];
}

// Save cart back to localStorage
function saveCart(cart) {
    localStorage.setItem('picknpay_cart', JSON.stringify(cart));
}

// Add item to cart
function addToCart(productId, name, price) {
    const cart = getCart();

    // Check if item already exists in cart
    const existingItem = cart.find(item => item.product_id === productId);

    if (existingItem) {
        // Just increase quantity
        existingItem.quantity += 1;
    } else {
        // Add as new item
        cart.push({
            product_id: productId,
            name: name,
            price: price,
            quantity: 1
        });
    }

    saveCart(cart);
    updateCartBadge();
}

// Update cart badge count in header
function updateCartBadge() {
    const cart = getCart();
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cartBadge').textContent = totalItems;
}



// Run on page load to show correct badge
updateCartBadge();

//showToast notification function
function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
// Listen for Add to Cart button clicks
document.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const productId = parseInt(btn.dataset.id);
        const name = btn.dataset.name;
        const price = parseFloat(btn.dataset.price);

        addToCart(productId, name, price);
        showToast('Added to cart');
    });
});