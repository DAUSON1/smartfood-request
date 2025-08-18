<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

// Update order status via GET
if(isset($_GET['update_status']) && isset($_GET['order_id'])){
    $order_id = intval($_GET['order_id']);
    $new_status = $_GET['update_status'];
    $allowed = ['pending','delivered','cancelled'];
    if(in_array($new_status,$allowed)){
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si",$new_status,$order_id);
        $stmt->execute();
        header("Location: manage_orders.php?status=$new_status");
        exit;
    }
}

// Filter orders by tab
$filter = $_GET['status'] ?? 'pending';
$allowed_filter = ['pending','delivered','cancelled'];
if(!in_array($filter,$allowed_filter)) $filter='pending';

$query = "SELECT o.id, u.name AS customer_name, f.name AS food_name, o.quantity, o.total, o.status
          FROM orders o
          JOIN users u ON o.user_id = u.id
          JOIN foods f ON o.food_id = f.id
          WHERE o.status='$filter'
          ORDER BY o.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        body { font-family: Arial; margin:0; padding:0; display:flex; height:100vh; background:#f4f4f4; }
        .sidebar { width:220px; background:#ff6347; color:white; padding-top:20px; display:flex; flex-direction:column; }
        .sidebar a { padding:15px 20px; text-decoration:none; color:white; font-weight:bold; display:block; transition:0.3s; }
        .sidebar a:hover { background:#e5533d; }
        .main { flex:1; padding:30px; overflow:auto; }
        h2 { color:#ff6347; text-align:center; margin-bottom:20px; }

        /* Tabs */
        .tabs { text-align:center; margin-bottom:20px; }
        .tabs a { padding:10px 15px; margin:0 5px; background:#ff6347; color:white; border-radius:5px; text-decoration:none; font-weight:bold; }
        .tabs a.active { background:#e5533d; }
        .tabs a:hover { background:#e5534d; }

        table { width:100%; border-collapse: collapse; background:white; border-radius:8px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.1); }
        th, td { padding:12px; text-align:center; border-bottom:1px solid #ddd; }
        th { background:#ff6347; color:white; }
        tr:hover { background:#f1f1f1; }

        .status-btn { padding:5px 10px; margin:2px; border:none; border-radius:5px; color:white; cursor:pointer; font-weight:bold; }
        .delivered { background:#5cb85c; }
        .cancelled { background:#d9534f; }
        .status-btn:hover { opacity:0.8; }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="dashboard.php">Dashboard</a>
    
    <a href="manage_orders.php">Orders</a>
   
</div>

<div class="main">
    <h2>Manage Orders</h2>

    <!-- Tabs -->
    <div class="tabs">
        <a href="manage_orders.php?status=pending" class="<?php if($filter=='pending') echo 'active'; ?>">Pending</a>
        <a href="manage_orders.php?status=delivered" class="<?php if($filter=='delivered') echo 'active'; ?>">Delivered</a>
        <a href="manage_orders.php?status=cancelled" class="<?php if($filter=='cancelled') echo 'active'; ?>">Cancelled</a>
    </div>

    <?php if($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Total (TZS)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['food_name']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo number_format($order['total'],0); ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td>
                        <?php if($filter=='pending'): ?>
                            <a class="status-btn delivered" href="manage_orders.php?order_id=<?php echo $order['id']; ?>&update_status=delivered">Deliver</a>
                            <a class="status-btn cancelled" href="manage_orders.php?order_id=<?php echo $order['id']; ?>&update_status=cancelled">Cancel</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center; margin-top:20px;">No orders in this section.</p>
    <?php endif; ?>
</div>

</body>
</html>
