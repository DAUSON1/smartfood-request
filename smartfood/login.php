<?php
session_start();
include "includes/db.php";

// Show errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role']
            ];

            // Redirect based on role
            if($user['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; }
        .container { background:white; padding:40px; border-radius:8px; box-shadow:0 0 15px rgba(0,0,0,0.2); width:350px; }
        h2 { text-align:center; color:#ff6347; margin-bottom:20px; }
        input[type=email], input[type=password] { width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
        button { width:100%; padding:10px; background:#ff6347; border:none; border-radius:5px; color:white; font-weight:bold; cursor:pointer; margin-top:10px; }
        button:hover { background:#e5533d; }
        .error { color:red; text-align:center; margin-bottom:10px; }
        a { color:#ff6347; text-decoration:none; font-weight:bold; }
        a:hover { text-decoration:underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p style="text-align:center; margin-top:10px;">Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
