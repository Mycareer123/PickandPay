<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <nav class="navbar">
        <span class="topbar">
            <span>
                <img src="../icons/add to cart svg.svg" alt="" class="svg">
                <h3 class="sysName">PicknPay</h3>
            </span>
            
            <!--vHamburger Buttonv-->
            <button class="hamburger" id="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </span>

        <ul class="navDrawer" id="navDrawer">
            <li>
                <a href="dashboard.php" class="nav-link" id="nav-link"><i class="ti ti-layout-dashboard"></i>Dashboard</a>
            </li>
            <li>
                <a href="product.php" class="nav-link"
                id="nav-link">Products</a>
            </li>
            <li>
                <a href="orders.php" class="nav-link" id="nav-link">Orders</a>
            </li>
            <li>
                <a href="brand.php" class="nav-link" id="nav-link">Brands</a>
            </li>
            <li>
                <a href="delivery.php" class="nav-link"     >Delivery Zones</a>
            </li>

            <li class="logout">
                <a href="../controllers/logout_process.php" class="nav-link"><button>Log Out</button></a>
            </li>
        </ul>
    </nav>
</body>
<script src="../assets/sidebar.js" defer></script>
</html>