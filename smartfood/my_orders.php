<?php
session_start();
include "includes/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer'){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Get user's orders
$query = "SELECT o.id, f.name as food_name, o.quantity, o.total, o.status
          FROM orders o
          JOIN foods f ON o.food_id = f.id
          WHERE o.user_id = $user_id
          ORDER BY o.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body { font-family: Arial; background:#f9f9f9; padding:20px; }
        h2 { color:#ff6347; text-align:center; margin-bottom:20px; }
        table { width:80%; margin:0 auto; border-collapse: collapse; background:white; border-radius:8px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.1);}
        th, td { padding:12px; text-align:center; border-bottom:1px solid #ddd; }
        th { background:#ff6347; color:white; }
        tr:hover { background:#f1f1f1; }
        .back-btn {
            display: block;
            width:180px;
            margin:20px auto;
            padding: 10px 15px;
            background: #ff6347;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            text-align:center;
            font-weight: bold;
        }
        .back-btn:hover { background:#e5533d; }
        p.no-orders { text-align:center; color:#555; font-size:18px; margin-top:30px; }
    </style>
</head>
<body>

<h2>My Orders</h2>

<?php if($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Food Name</th>
            <th>Quantity</th>
            <th>Total (TZS)</th>
            <th>Status</th>
        </tr>
        <?php while($order = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['food_name']); ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo number_format($order['total'],0); ?></td>
                <td><?php echo ucfirst($order['status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p class="no-orders">You have not placed any orders yet.</p>
<?php endif; ?>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

</body>
</html>
