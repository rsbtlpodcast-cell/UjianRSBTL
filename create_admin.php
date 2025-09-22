<?php
require 'db.php';

$message = "";
$admin_exists = false;

// Cek apakah sudah ada admin
$stmt = $pdo->prepare("SELECT * FROM users WHERE role='admin'");
$stmt->execute();
$admin = $stmt->fetch();

if($admin){
    $admin_exists = true;
    $message = "Admin sudah ada. Username: <strong>".$admin['username']."</strong>";
}

// Proses form submit
if($_SERVER['REQUEST_METHOD'] === 'POST' && !$admin_exists){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if($username && $password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
        if($stmt->execute([$username, $hash])){
            $message = "Admin berhasil dibuat!<br>Username: <strong>$username</strong><br>Password: <strong>$password</strong>";
            $admin_exists = true;
        } else {
            $message = "Gagal membuat admin.";
        }
    } else {
        $message = "Username dan password tidak boleh kosong.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Buat Admin Pertama - RSUD</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body {margin:0; font-family:'Poppins',sans-serif; background:linear-gradient(135deg,#f0f0f0,#ffffff); display:flex; justify-content:center; align-items:center; height:100vh;}
.card {background:white; padding:40px; border-radius:20px; box-shadow:0 12px 28px rgba(0,0,0,0.12); width:360px; text-align:center;}
h2 {color:#2C6ED5; margin-top:0;}
input {width:100%; padding:12px 15px; margin-bottom:15px; border:1px solid #ddd; border-radius:10px; font-size:15px;}
button {width:100%; padding:14px; background:#2C6ED5; color:#fff; font-weight:600; border:none; border-radius:12px; cursor:pointer; transition:0.3s;}
button:hover {background:#1A4FA0;}
.message {margin-bottom:15px; padding:12px; border-radius:10px; background:#e6f0ff; color:#2C6ED5;}
</style>
</head>
<body>
<div class="card">
<h2>Buat Admin Pertama</h2>
<?php if($message) echo "<div class='message'>$message</div>"; ?>
<?php if(!$admin_exists): ?>
<form method="post">
    <input type="text" name="username" placeholder="Username admin" required>
    <input type="password" name="password" placeholder="Password admin" required>
    <button type="submit">Buat Admin</button>
</form>
<p style="margin-top:15px; font-size:14px; color:#555;">Setelah admin dibuat, hapus file ini demi keamanan.</p>
<?php endif; ?>
</div>
</body>
</html>
