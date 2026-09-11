//navbar dropdown functionality
let btn = document.getElementById('hamburger');
let draw = document.getElementById('navDrawer');
draw.style.display = 'none'
function displayBlock(button) {
    if (button.style.display == 'none') {
        return false;
    }
    return true;
}

function altDisplay(button) {
    if (displayBlock(button)) {
        button.style.display = 'none';
    } else {
        button.style.display = 'block';
    }
}

btn.addEventListener('click', () => {
    altDisplay(draw);
});


// ─── MODE FLAG ───────────────────────────────
let currentMode = 'add';
let currentProductId = null;


// DISPLAY ADD MODAL
const addModal = document.getElementById('addModal');
const addBtn = document.getElementById('openAddModal');

addBtn.addEventListener('click', () => {
    currentMode = 'add';           // reset mode to add
    currentProductId = null;       // reset product id
    selectedFiles = [];     // reset selected files
    removedImageIds = [];   // add this
    existingImages = [];    
    addModal.querySelector(".btn-text").textContent = "Save Product";
    addModal.querySelector(".modal-title").textContent = "Add Product";
    addModal.classList.add('open');

    document.getElementById("productName").value = "";
    document.getElementById("productPrice").value = "";
    document.getElementById("productStock").value = "";
    document.getElementById("productStatus").value = "Active";
    
    
});

document.getElementById('closeAddModal').addEventListener('click', () => {
    addModal.classList.remove('open');
    currentMode = 'add';           // reset mode on cancel
    currentProductId = null;
});

addModal.addEventListener('click', (e) => {
    if (e.target === addModal) {
        addModal.classList.remove('open');
        currentMode = 'add';
        currentProductId = null;
    }
});


// ─── IMAGE UPLOAD ────────────────────────────
const uploadArea = document.getElementById('uploadArea');
const imageInput = document.getElementById('imageInput');
const imagePreviews = document.getElementById('imagePreviews');

let selectedFiles = [];

uploadArea.addEventListener('click', () => {
    imageInput.click();
});

imageInput.addEventListener('change', (e) => {
    const newFiles = Array.from(e.target.files);
    selectedFiles = selectedFiles.concat(newFiles);
    renderPreviews();
    imageInput.value = '';
});

function renderPreviews() {
    imagePreviews.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            const thumb = document.createElement('div');
            thumb.className = 'preview-thumb';
            thumb.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <button type="button" class="preview-remove" data-index="${index}">
                    <i class="ti ti-x"></i>
                </button>
            `;
            imagePreviews.appendChild(thumb);
        };

        reader.readAsDataURL(file);
    });
}

// ─── TOAST ───────────────────────────────────
function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}


// ─── SAVE BUTTON ─────────────────────────────
const addProductForm = document.getElementById('addProductForm');
const saveBtn = document.getElementById('saveProductBtn');

saveBtn.addEventListener('click', async () => {
    const formData = new FormData(addProductForm);

    formData.delete('images[]');
    selectedFiles.forEach((file) => {
        formData.append('images[]', file);
    });

    if (currentMode === 'edit') {
        // send to update_product.php
        formData.append('product_id', currentProductId);
        formData.append('removed_ids', JSON.stringify(removedImageIds));

        const response = await fetch('../controllers/update_product.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            addModal.classList.remove('open');
            showToast(result.message);
            selectedFiles = [];       // reset state
            removedImageIds = [];
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(result.message);
        }

    } else {  
        // send to add_product.php
        const response = await fetch('../controllers/add_product.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            addModal.classList.remove('open');
            addProductForm.reset();
            selectedFiles = [];
            renderPreviews();
            showToast(result.message);

            setTimeout(() => location.reload(), 800);
        } else {
            showToast(result.message);
        }
    }
});


// ─── SEARCH ──────────────────────────────────
const searchInput = document.querySelector("#searchInput");
const itemName = document.querySelectorAll(".product-name");
const card = document.querySelectorAll('.product-card');

searchInput.addEventListener("input", () => {
    card.forEach((cardItem, i) => {
        const name = (itemName[i]?.dataset.name || "").toLowerCase();
        if (name.includes(searchInput.value.toLowerCase())) {
            cardItem.style.display = 'flex';
        } else {
            cardItem.style.display = "none";
        }
    });
});

let existingImages = [];   // images already in the DB: {id, url, name}
let removedImageIds = [];  // existing images the user marked for deletion


async function loadExistingImages(productId) {
    console.log('loadExistingImages called with:', productId);
    const response = await fetch(`../controllers/get_images.php?id=${productId}`);

    const data = await response.json();
    existingImages = data.images;

    renderPreviewsEdits();
}

function renderPreviewsEdits() {
    imagePreviews.innerHTML = '';

    // Render existing (already-saved) images
    existingImages
        .filter(img => !removedImageIds.includes(img.id))
        .forEach(img => {
            const thumb = document.createElement('div');
            thumb.className = 'preview-thumb';
            thumb.innerHTML = `
                <img src="${img.url}" alt="${img.name}">
                <button type="button" class="preview-remove" data-type="existing" data-id="${img.id}">
                    <i class="ti ti-x"></i>
                </button>
            `;
            imagePreviews.appendChild(thumb);
        });

    // Render newly selected local files
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const thumb = document.createElement('div');
            thumb.className = 'preview-thumb';
            thumb.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <button type="button" class="preview-remove" data-type="new" data-index="${index}">
                    <i class="ti ti-x"></i>
                </button>
            `;
            imagePreviews.appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
}

// Handle the remove button clicks (delegated)
imagePreviews.addEventListener('click', (e) => {
    const btn = e.target.closest('.preview-remove');
    if (!btn) return;

    if (btn.dataset.type === 'existing') {
        removedImageIds.push(parseInt(btn.dataset.id));
    } else {
        selectedFiles.splice(parseInt(btn.dataset.index), 1);
    }
    renderPreviewsEdits();
});


// ─── EDIT PRODUCT ────────────────────────────
const editBtn = document.querySelectorAll(".edit");

editBtn.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
        selectedFiles = [];       // add this
        removedImageIds = [];   
        const card = e.target.closest(".product-card");

        addModal.classList.add("open");
        addModal.querySelector('.modal-title').textContent = "Edit Product";
        addModal.querySelector(".btn-text").textContent = "Save Edit";

        async function getCardDetails(card) {
            const id = card.dataset.id;

            if (!id) {
                console.error("Invalid Card Id");
                return;
            }

            const res = await fetch(`../controllers/fetch_product_details.php?id=${id}`);
            const productDetails = await res.json();
            const product = productDetails.product;
            const productImages = productDetails.images;
            

            if (productDetails.status === "success") {   // fixed: was "successful"
                document.getElementById('productName').value = product.product_name;
                document.getElementById('productPrice').value = product.price_unit;
                document.getElementById('productStock').value = product.quantity;
                if (product.status === 1) {
                    document.getElementById('productStatus').value = "Active";
                } else {
                    document.getElementById('productStatus').value = "Inactive";
                }

                // set mode and product id
                currentMode = 'edit';
                currentProductId = id;
            }
            
        }
        await getCardDetails(card);
        await loadExistingImages(card.dataset.id);
        
        
    });
});


// Delete Product
const deleteBtn = document.querySelectorAll(".delete");

deleteBtn.forEach((deleteBtn) => {
    deleteBtn.addEventListener('click', (e)=>{
        const id = deleteBtn.dataset.id;

        const deleteModal = document.getElementById("confirmModal");
        deleteModal.style.display = "flex";

        const cancelBtn = document.getElementById("cancelDelete");
        cancelBtn.addEventListener("click", ()=>{
            deleteModal.style.display ="none";
        });

        const confirmBtn = document.getElementById("confirmDelete");
        confirmBtn.addEventListener("click", async(e)=>{
            if(!id){
                console.log("Invalid card Id");
                return;
            }
            const formData = new FormData();
            formData.append("id", id);
            const request = await fetch("../controllers/delete_product.php",{
                method: "POST",
                body: formData
            });

            const response = await request.json();

            if(response.status === "success"){
                deleteModal.style.display = "none";
                showToast(response.message);
                setTimeout(()=>location.reload(), 800);
            }
        });
    });
})