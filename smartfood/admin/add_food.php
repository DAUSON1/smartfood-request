<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$error = "";
$success = "";

if(isset($_POST['add_food'])){
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);

    // Image upload
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir,0777,true);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        
        if(in_array($imageFileType,$allowed)){
            if(move_uploaded_file($_FILES['image']['tmp_name'],$target_file)){
                // Insert into database
                $stmt = $conn->prepare("INSERT INTO foods (name, price, image) VALUES (?,?,?)");
                $stmt->bind_param("sds",$name,$price,$image_name);
                if($stmt->execute()){
                    $success = "Food added successfully!";
                } else {
                    $error = "Database insert failed.";
                }
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, PNG, GIF files allowed.";
        }
    } else {
        $error = "Please select an image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Food</title>
    <style>
        body { font-family: Arial; margin:0; padding:0; display:flex; height:100vh; background:#f4f4f4; }
        .sidebar { width:220px; background:#ff6347; color:white; padding-top:20px; display:flex; flex-direction:column; }
        .sidebar a { padding:15px 20px; text-decoration:none; color:white; font-weight:bold; display:block; transition:0.3s; }
        .sidebar a:hover { background:#e5533d; }
        .main { flex:1; padding:40px; overflow:auto; }
        h2 { color:#ff6347; text-align:center; margin-bottom:20px; }
        form { background:white; padding:30px; border-radius:8px; max-width:500px; margin:0 auto; box-shadow:0 0 10px rgba(0,0,0,0.1);}
        input[type=text], input[type=number], input[type=file] { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
        button { padding:10px 20px; background:#ff6347; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:bold; }
        button:hover { background:#e5533d; }
        .error { color:red; text-align:center; margin-bottom:10px; }
        .success { color:green; text-align:center; margin-bottom:10px; }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="dashboard.php">Dashboard</a>
    <a href="add_food.php">Add Food</a>
   
    
</div>

<div class="main">
    <h2>Add New Food</h2>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Food Name" required>
        <input type="number" name="price" placeholder="Price (TZS)" step="0.01" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_food">Add Food</button>
    </form>
</div>

</body>
</html>
