<?php
session_start();
include "../includes/db.php"; // adjust path if needed

$admin_error = "";
if(isset($_POST['admin_login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "admin" && $password == "1234"){
        $_SESSION['user'] = [
            'id' => 0,
            'name' => 'Administrator',
            'role' => 'admin'
        ];
        header("Location: dashboard.php"); // adjust if dashboard.php path
        exit;
    } else {
        $admin_error = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial; background:#f9f9f9; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .login-box { background:#fff; padding:30px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:300px; }
        h2 { text-align:center; color:#ff6347; margin-bottom:20px; }
        input { width:100%; padding:10px; margin:5px 0 15px 0; border:1px solid #ccc; border-radius:5px; }
        button { width:100%; padding:10px; background:#ff6347; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:bold; }
        button:hover { background:#e5533d; }
        .error { color:red; text-align:center; margin-bottom:10px; }
        a { text-decoration:none; display:block; text-align:center; margin-top:10px; color:#555; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if($admin_error) echo "<div class='error'>$admin_error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="admin_login">Login</button>
        </form>
        <a href="../index.php">Back to Home</a>
    </div>
</body>
</html>
