<?php
    session_start();
    include "../../config/session.php";
    require "../../config/db.php";


    $sql = 'select * from products order by product_id desc';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $productCount = count($products);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - PicknPay Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/product.css">
</head>
<body>

<!-- ─── TOPBAR ─────────────────────────────────── -->
<div class="topbar">
    <div class="topbar-brand">
        <i class="ti ti-shopping-cart"></i>
        PicknPay
    </div>
    <div class="topbar-right">
        <span class="topbar-admin">Admin</span>
        <button class="hamburger" id="hamburger" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
    </div>
</div>

<!-- ─── NAV DRAWER ────────────────────────────── -->
<div class="nav-drawer" id="navDrawer">
    <a href="dashboard.php" class="nav-item">
        <i class="ti ti-layout-dashboard"></i> Dashboard
    </a>
    <a href="products.php" class="nav-item active">
        <i class="ti ti-package"></i> Products
    </a>
    <a href="orders.php" class="nav-item">
        <i class="ti ti-shopping-bag"></i> Orders
    </a>
    <a href="brands.php" class="nav-item">
        <i class="ti ti-building-store"></i> Brands
    </a>
    <a href="delivery.php" class="nav-item">
        <i class="ti ti-truck-delivery"></i> Delivery Zones
    </a>
    <a href="logout.php" class="nav-item nav-logout">
        <i class="ti ti-logout"></i> Logout
    </a>
</div>

<!-- ─── MAIN CONTENT ──────────────────────────── -->
<div class="main">
    <div class="page-head">
        <div>
            <p class="page-title">Products</p>
            <p class="page-sub" id="productCount">
                <?php
                    if($productCount > 0) {
                        echo "Total of: ".$productCount ."   Products";
                    } else {
                        echo "Loading...";
                    } 
                ?> 
           </p>
        </div>
        <button class="add-btn" id="openAddModal">
            <i class="ti ti-plus"></i> Add
        </button>
    </div>

    <div class="search-box">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Search products..." />
    </div>

    <!-- Product list injected by PHP/JS here -->
    <div id="productList">
        <?php foreach ($products as $product): ?>
        <div class="product-card" data-id="<?= $product['product_id'] ?>">
            <div class="product-thumb">
                <?php
                    $sql = "select * from product_images where product_id = :product_id order by display_order asc limit 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':product_id' => $product['product_id']]);
                    $productImage = $stmt->fetch(PDO::FETCH_ASSOC); 
                ?>
                <?php if ($productImage): ?>
                    <img src="<?= $productImage['image_url'] ?>" alt="<?= $product['product_name'] ?>">
                <?php else: ?>
                    <i class="ti ti-package"></i>
                <?php endif; ?>
            </div>
            <div class="product-info">
                <div class="product-name" data-name ="<?php echo $product['product_name']; ?>"><?= $product['product_name'] ?></div>
                <div class="product-price">GH₵<?= $product['price_unit'] ?></div>
                <div class="product-meta">
                    <span class="stock-text">Stock: <?= $product['quantity'] ?></span>
                    <span class="status-badge <?= $product['status'] == 1 ? 'status-active' : 'status-inactive' ?>">
                        <?= $product['status'] == 1 ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>
            <div class="product-actions">
                <button class="icon-btn edit" data-id="<?= $product['product_id'] ?>">
                    <i class="ti ti-edit"></i>
                </button>
                <button class="icon-btn delete" data-id="<?= $product['product_id'] ?>">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>`
    </div>

    <!-- Pagination -->
   
</div>

<!-- ─── ADD PRODUCT MODAL ─────────────────────── -->
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-handle"></div>
        <p class="modal-title">Add Product</p>
        <p class="modal-sub">Fill in the details below</p>


        <!-- Form for adding a product -->
        <form id="addProductForm" method="POST" enctype="multipart/form-data">
            <!-- Image Upload -->
            <div class="image-upload-area" id="uploadArea">
                <i class="ti ti-cloud-upload"></i>
                <span class="upload-label">Tap to upload images</span>
                <span class="upload-hint">PNG, JPG up to 5MB each — multiple allowed</span>
                <div class="image-previews" id="imagePreviews"></div>
            </div>
            <input type="file" id="imageInput" name="images[]" accept="image/*" multiple>
        
            <label class="field-label">Product Name <span class="required">*</span></label>
            <input class="field-input" type="text" name="product_name" id="productName" placeholder="e.g. Double Door Refrigerator 300L" />

            <div class="field-row">
                <div>
                    <label class="field-label">Price (GH₵) <span class="required">*</span></label>
                    <input class="field-input" type="number" name="price" id="productPrice" placeholder="0.00" min="0" step="0.01" />
                </div>
                <div>
                    <label class="field-label">Stock Qty <span class="required">*</span></label>
                    <input class="field-input" type="number" name="stock_quantity" id="productStock" placeholder="0" min="0" />
                </div>
            </div>

            <label class="field-label">Status</label>
            <select class="field-select" name="status" id="productStatus">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <label class="field-label">Description</label>
            <textarea class="field-textarea" name="description" id="productDesc" placeholder="Short product description..."></textarea>

            <div class="form-actions">
                <button type="button" class="cancel-btn" id="closeAddModal">Cancel</button>
                <button type="button" class="save-btn" id="saveProductBtn">
                    <span class="btn-text">Save Product</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ─── DELETE CONFIRM MODAL ──────────────────── -->
<div class="confirm-overlay" id="confirmModal">
    <div class="confirm-box">
        <div class="confirm-icon">
            <i class="ti ti-alert-triangle"></i>
        </div>
        <p class="confirm-title">Delete Product?</p>
        <p class="confirm-text" id="confirmText">This will permanently remove this product. This action cannot be undone.</p>
        <div class="confirm-actions">
            <button class="confirm-cancel" id="cancelDelete">Cancel</button>
            <button class="confirm-delete" id="confirmDelete">Yes, Delete</button>
        </div>
    </div>
</div>

<!-- ─── TOAST ─────────────────────────────────── -->
<div class="toast" id="toast"></div>


<script src="../assets/product.js" defer>

</script>
</body>
</html>