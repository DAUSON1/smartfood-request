<?php
session_start();
include "includes/db.php";

$error = "";
$success = "";

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $error = "Email already registered.";
    } else {
        $hashed = password_hash($password,PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $role = "customer";
        $stmt2->bind_param("ssss",$name,$email,$hashed,$role);
        if($stmt2->execute()){
            $success = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; }
        .container { background:white; padding:40px; border-radius:8px; box-shadow:0 0 15px rgba(0,0,0,0.2); width:350px; }
        h2 { text-align:center; color:#ff6347; margin-bottom:20px; }
        input[type=text], input[type=email], input[type=password] { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
        button { width:100%; padding:10px; background:#ff6347; border:none; border-radius:5px; color:white; font-weight:bold; cursor:pointer; margin-top:10px; }
        button:hover { background:#e5533d; }
        .error { color:red; text-align:center; margin-bottom:10px; }
        .success { color:green; text-align:center; margin-bottom:10px; }
        a { color:#ff6347; text-decoration:none; font-weight:bold; }
        a:hover { text-decoration:underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Customer Register</h2>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
</div>

</body>
</html>
