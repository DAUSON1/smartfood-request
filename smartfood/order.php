<?php
session_start();
include "includes/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer'){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$food_id = $_POST['food_id'] ?? 0;
$error = "";
$success = "";

// Get food details
$food_result = $conn->query("SELECT * FROM foods WHERE id=$food_id LIMIT 1");
if($food_result && $food_result->num_rows > 0){
    $food = $food_result->fetch_assoc();
} else {
    die("Food not found.");
}

// Handle order submission
if(isset($_POST['place_order'])){
    $quantity = intval($_POST['quantity']);
    if($quantity < 1){
        $error = "Quantity must be at least 1.";
    } else {
        $total = $food['price'] * $quantity;
        $stmt = $conn->prepare("INSERT INTO orders (user_id, food_id, quantity, total, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iiid", $user_id, $food_id, $quantity, $total);
        if($stmt->execute()){
            $success = "Order placed successfully!";
            // Optional: redirect to my_orders.php after 1 second
            header("Refresh:1; url=my_orders.php");
        } else {
            $error = "Failed to place order.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <style>
        body { font-family: Arial; background:#f9f9f9; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .order-box { background:#fff; padding:30px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:350px; text-align:center; }
        h2 { color:#ff6347; margin-bottom:20px; }
        input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
        button { padding:10px 20px; background:#ff6347; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:bold; }
        button:hover { background:#e5533d; }
        .error { color:red; margin-bottom:10px; }
        .success { color:green; margin-bottom:10px; }
        /* Back button style */
        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #ff6347;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }
        .back-btn:hover {
            background: #e5533d;
        }
    </style>
</head>
<body>
    <div class="order-box">
        <h2>Place Order</h2>
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <?php if($success) echo "<div class='success'>$success</div>"; ?>
        <form method="POST">
            <p><strong><?php echo htmlspecialchars($food['name']); ?></strong></p>
            <p>Price: <?php echo number_format($food['price'],0); ?> TZS</p>
            <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
            <input type="number" name="quantity" placeholder="Quantity" value="1" min="1" required>
            <button type="submit" name="place_order">Place Order</button>
        </form>

        <p><a href="dashboard.php" class="back-btn">← Back to Dashboard</a></p>
    </div>
</body>
</html>
