<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN - ADMIN</title>
    <link rel="stylesheet" href="assets/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playwrite+GB+J:ital,wght@0,100..400;1,100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
    <div class="initDiv">
        <img src="icons/add to cart svg.svg" alt="" class="svg">
        <h3 class="sysName">PicknPay Admin</h3>
        <p class="tagline">Manage Your Store</p>

        <div class="card">
            <p class="errMsg" id="mainErr"></p>
            <form action="views/dashboard.php" method="POST" id="logInForm">
            <label for="email" class="cardLabel">Email Address</label>
            <input class="cardInput" type="email" name="email" id="email">
            <p class="errMsg" id="emailErr"></p>

            <label for="password" class="cardLabel">Password</label>
            <div class="flexDiv">
                <input type="password" class="cardInput" name="password" id="password">
                <button type="button" id="togglePassword" aria-label="Toggle password visibility">
                    <!-- SVG Eye Icon -->
                    <svg xmlns="http://w3.org" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
            </button>
            </div>
            <p class="errMsg" id="passwordErr"></p>
            <p class="fgtPass">Forgot Password?</p>
            
            <button class="lgnBtn" type="button">
                <span class="btnText">Log In</span>

                <span class = "btnSpinner" id="btnSpinner"></span>
            </button>
            
            </form>
        </div>
    </div>
    <script src="assets/login.js" defer></script>
</body>
</html>