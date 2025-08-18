<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Food Management System</title>
    <style>
        body { font-family: Arial; margin:0; padding:0; background:#f9f9f9; }
        /* Topbar */
        .topbar { background:#ff6347; padding:15px 30px; color:white; display:flex; justify-content:space-between; align-items:center; }
        .topbar a { color:white; text-decoration:none; margin-left:20px; font-weight:bold; }
        .topbar a:hover { text-decoration:underline; }
        /* Main Section */
        .main { text-align:center; padding:80px 20px; background:#fff; }
        .main h1 { font-size:36px; color:#333; }
        .main p { font-size:18px; color:#555; max-width:600px; margin:auto; }
        /* Info Section */
        .info { display:flex; justify-content:center; gap:40px; margin:50px 0; flex-wrap:wrap; }
        .info div { background:#fff; padding:20px; width:220px; text-align:center; box-shadow:0 0 10px rgba(0,0,0,0.1); border-radius:8px; }
        .info h3 { color:#ff6347; margin-bottom:10px; }
    </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo"><strong>Smart Food</strong></div>
    <div class="links">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="admin/admin_login.php">Manager</a>
    </div>
</div>

<!-- Main Welcome Section -->
<div class="main">
    <h1>Welcome to Smart Food Management System</h1>
    <p>Order your favorite meals easily from your computer or smartphone. Fast, reliable, and simple!</p>
</div>

<!-- Info Section -->
<div class="info">
    <div>
        <h3>About Us</h3>
        <p>We are dedicated to delivering fresh and delicious food to our customers quickly and efficiently.</p>
    </div>
    <div>
        <h3>Contact Us</h3>
        <p>Email: support@smartfood.com<br>Phone: +255 700 123 456</p>
    </div>
    <div>
        <h3>Help</h3>
        <p>Need assistance? Check our FAQ or contact our support team for quick help.</p>
    </div>
</div>

</body>
</html>
