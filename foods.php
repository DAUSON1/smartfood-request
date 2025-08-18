<?php
include "includes/db.php";

$result = $conn->query("SELECT * FROM foods");
while($row = $result->fetch_assoc()){
    echo "<div>";
    echo "<h3>".$row['name']."</h3>";
    echo "<p>".$row['description']."</p>";
    echo "<p>Price: ".$row['price']." TZS</p>";
    echo "<img src='images/".$row['image']."' width='100'><br>";
    echo "<a href='order.php?food_id=".$row['id']."'>Order Now</a>";
    echo "</div><hr>";
}
?>
<?php include "includes/navbar.php"; ?>

