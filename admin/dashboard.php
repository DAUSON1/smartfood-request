<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$admin_name = $_SESSION['user']['name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; margin:0; padding:0; background:#f4f4f4; display:flex; height:100vh; }
        /* Sidebar */
        .sidebar { width:220px; background:#ff6347; color:white; padding-top:20px; display:flex; flex-direction:column; }
        .sidebar a {
            padding:15px 20px;
            text-decoration:none;
            color:white;
            font-weight:bold;
            display:block;
            transition:0.3s;
        }
        .sidebar a:hover { background:#e5533d; }
        /* Main Content */
        .main { flex:1; padding:40px; overflow:auto; background:#f9f9f9; }
        .welcome { text-align:center; margin-top:50px; }
        .welcome h1 { color:#ff6347; font-size:36px; }
        .welcome p { font-size:20px; color:#555; margin-top:15px; }
        /* Responsive */
        @media(max-width:768px){
            body { flex-direction:column; }
            .sidebar { width:100%; flex-direction:row; justify-content:space-around; }
            .main { padding:20px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <a href="add_food.php">Add Food</a>
    <a href="manage_orders.php">Manage Orders</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="main">
    <div class="welcome">
        <h1>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h1>
        <p>Use the sidebar to manage foods and orders efficiently.</p>
        <p>Monitor customer orders and keep the menu updated.</p>
    </div>
</div>

</body>
</html>
