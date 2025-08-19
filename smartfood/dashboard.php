<?php
session_start();
include "includes/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer'){
    header("Location: login.php");
    exit;
}

$name = $_SESSION['user']['name'];

// Handle search
$search = "";
if(isset($_GET['search'])){
    $search = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM foods WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM foods";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <style>
        body { font-family: Arial; margin:0; padding:0; background:#f9f9f9; }
        /* Topbar */
        .topbar { background:#ff6347; padding:15px 30px; color:white; display:flex; justify-content:space-between; align-items:center; }
        .topbar a { color:white; text-decoration:none; margin-left:20px; font-weight:bold; }
        .topbar a:hover { text-decoration:underline; }
        /* Main Section */
        .main { padding:30px; text-align:center; }
        h1 { color:#333; }
        /* Search Bar */
        .search-box { margin-bottom:20px; }
        .search-box input[type="text"] { padding:10px; width:250px; border-radius:5px; border:1px solid #ccc; }
        .search-box button { padding:10px 15px; border:none; border-radius:5px; background:#ff6347; color:white; cursor:pointer; }
        .search-box button:hover { background:#e5533d; }
        /* Foods Carousel */
        .carousel-container { overflow:hidden; white-space: nowrap; margin-top:20px; }
        .food-item { display:inline-block; width:200px; margin-right:15px; background:white; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); vertical-align:top; }
        .food-item img { width:100%; height:150px; border-top-left-radius:8px; border-top-right-radius:8px; }
        .food-info { padding:10px; }
        .food-info h3 { margin:5px 0; color:#ff6347; }
        .food-info p { margin:5px 0; color:#555; }
        .food-info button { padding:8px 12px; background:#ff6347; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:5px; }
        .food-info button:hover { background:#e5533d; }
    </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div>Welcome, <?php echo htmlspecialchars($name); ?>!</div>
    <div>
        <a href="my_orders.php">My Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Main Section -->
<div class="main">
    <h1>Available Foods</h1>

    <!-- Search Form -->
    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="Search food by name..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Foods Carousel -->
    <div class="carousel-container" id="carousel">
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($food = $result->fetch_assoc()): ?>
                <div class="food-item">
                    <img src="uploads/<?php echo $food['image']; ?>" alt="<?php echo htmlspecialchars($food['name']); ?>">

                    <div class="food-info">
                        <h3><?php echo htmlspecialchars($food['name']); ?></h3>
                        <p><?php echo number_format($food['price'], 0); ?> TZS</p>
                        <form method="POST" action="order.php">
                            <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                            <button type="submit">Order Now</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No foods available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
// Auto-slide carousel every 5 seconds
const carousel = document.getElementById('carousel');
let scrollAmount = 0;
setInterval(() => {
    if(carousel.scrollWidth - carousel.clientWidth <= scrollAmount){
        scrollAmount = 0; // reset to start
    } else {
        scrollAmount += 220; // scroll width of one item + margin
    }
    carousel.scrollTo({ left: scrollAmount, behavior: 'smooth' });
}, 5000);
</script>

</body>
</html>
